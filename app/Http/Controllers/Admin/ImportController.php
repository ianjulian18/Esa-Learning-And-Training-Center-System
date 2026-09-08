<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ImportBatch;
use App\Models\ImportRow;
use App\Models\User;
use App\Models\EmploymentHistory;
use App\Models\Principal;
use App\Models\Position;
use App\Models\Department;
use App\Services\AssignmentEngine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ImportController extends Controller
{
    public function index()
    {
        $batches = ImportBatch::orderBy('created_at', 'desc')->take(10)->get();
        return Inertia::render('Admin/Imports/Index', [
            'batches' => $batches
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240'
        ]);

        $file = $request->file('file');
        $csvData = array_map('str_getcsv', file($file->getRealPath()));
        $header = array_shift($csvData);
        // Expect headers: name, email, nik, password, principal_id, position_id, department_id, join_date

        $batch = ImportBatch::create([
            'admin_id' => $request->user()->id,
            'status' => 'PROCESSING',
            'total_rows' => count($csvData),
            'processed_rows' => 0,
            'failed_rows' => 0
        ]);

        $processed = 0;
        $failed = 0;

        foreach ($csvData as $idx => $row) {
            if (count($header) !== count($row)) {
                $failed++;
                continue;
            }
            
            $data = array_combine($header, $row);
            
            try {
                DB::beginTransaction();

                // 1. Create or Update User
                $user = User::updateOrCreate(
                    ['email' => $data['email']],
                    [
                        'name' => $data['name'],
                        'password' => Hash::make($data['password'] ?? 'password123')
                    ]
                );

                // Attach role Learner if needed
                if (!$user->hasRole('Learner')) {
                    $user->assignRole('Learner');
                }

                // Lookup IDs by Name (Case Insensitive)
                $principal = Principal::whereRaw('LOWER(name) = ?', [strtolower(trim($data['principal']))])->first();
                if (!$principal) throw new \Exception('Principal name not found: ' . $data['principal']);

                $position = Position::whereRaw('LOWER(name) = ?', [strtolower(trim($data['position']))])->first();
                if (!$position) throw new \Exception('Position name not found: ' . $data['position']);

                $departmentId = null;
                if (!empty($data['department'])) {
                    $department = Department::whereRaw('LOWER(name) = ?', [strtolower(trim($data['department']))])->first();
                    if (!$department) throw new \Exception('Department name not found: ' . $data['department']);
                    $departmentId = $department->id;
                }

                // 2. Create Employment History
                // Close previous active histories
                EmploymentHistory::where('user_id', $user->id)
                    ->where('status', 'ACTIVE')
                    ->update([
                        'status' => 'INACTIVE',
                        'end_date' => now()
                    ]);

                // Create new active history
                EmploymentHistory::create([
                    'user_id' => $user->id,
                    'principal_id' => $principal->id,
                    'position_id' => $position->id,
                    'department_id' => $departmentId,
                    'nik' => $data['nik'],
                    'join_date' => $data['join_date'] ?? now()->toDateString(),
                    'status' => 'ACTIVE'
                ]);

                // 3. Trigger Assignment Engine
                AssignmentEngine::evaluateUser($user);

                DB::commit();
                $processed++;
            } catch (\Exception $e) {
                DB::rollBack();
                $failed++;
                ImportRow::create([
                    'import_batch_id' => $batch->id,
                    'row_number' => $idx + 2,
                    'data' => $data,
                    'status' => 'FAILED',
                    'error_message' => $e->getMessage()
                ]);
            }
        }

        $batch->update([
            'status' => 'COMPLETED',
            'processed_rows' => $processed,
            'failed_rows' => $failed
        ]);

        return back()->with('success', "Import completed. Processed: $processed, Failed: $failed");
    }

    public function downloadTemplate()
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=import_template.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['name', 'email', 'nik', 'password', 'principal', 'position', 'department', 'join_date'];
        $dummyData = ['John Doe', 'john@example.com', '123456789', 'password123', 'PT. Utama', 'Staff IT', 'Teknologi Informasi', '2023-01-01'];

        $callback = function() use($columns, $dummyData) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            fputcsv($file, $dummyData);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}



