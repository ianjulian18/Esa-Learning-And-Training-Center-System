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

    private function validateAndParseCsv(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240'
        ]);

        $file = $request->file('file');
        $csvData = array_map('str_getcsv', file($file->getRealPath()));
        $header = array_shift($csvData);
        return [$header, $csvData];
    }

    private function resolveReferences($data)
    {
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

        return [$principal->id, $position->id, $departmentId];
    }

    public function storeCreate(Request $request)
    {
        list($header, $csvData) = $this->validateAndParseCsv($request);

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

                // 9.1 Import Create: Jika unique key sudah ada: ERROR.
                $existingUser = User::where('email', $data['email'])->orWhere('nik', $data['nik'])->first();
                if ($existingUser) {
                    throw new \Exception('User already exists (NIK/Email). Cannot Create.');
                }

                $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'nik' => $data['nik'],
                    'password' => Hash::make($data['password'] ?? 'password123')
                ]);
                $user->assignRole('Learner');

                list($principalId, $positionId, $departmentId) = $this->resolveReferences($data);

                EmploymentHistory::create([
                    'user_id' => $user->id,
                    'principal_id' => $principalId,
                    'position_id' => $positionId,
                    'department_id' => $departmentId,
                    'nik' => $data['nik'],
                    'join_date' => $data['join_date'] ?? now()->toDateString(),
                    'status' => 'ACTIVE'
                ]);

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

        $batch->update(['status' => 'COMPLETED', 'processed_rows' => $processed, 'failed_rows' => $failed]);
        return back()->with('success', "Import Create completed. Processed: $processed, Failed: $failed");
    }

    public function storeUpdate(Request $request)
    {
        list($header, $csvData) = $this->validateAndParseCsv($request);

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

                // 9.2 Import Update: Jika unique key tidak ditemukan: ERROR. Tidak boleh membuat data baru.
                $user = User::where('email', $data['email'])->orWhere('nik', $data['nik'])->first();
                if (!$user) {
                    throw new \Exception('User not found. Cannot Update.');
                }

                // Update basic info if provided
                if (!empty($data['name'])) $user->name = $data['name'];
                if (!empty($data['password'])) $user->password = Hash::make($data['password']);
                $user->save();

                list($principalId, $positionId, $departmentId) = $this->resolveReferences($data);

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
                    'principal_id' => $principalId,
                    'position_id' => $positionId,
                    'department_id' => $departmentId,
                    'nik' => $data['nik'],
                    'join_date' => $data['join_date'] ?? now()->toDateString(),
                    'status' => 'ACTIVE'
                ]);

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

        $batch->update(['status' => 'COMPLETED', 'processed_rows' => $processed, 'failed_rows' => $failed]);
        return back()->with('success', "Import Update completed. Processed: $processed, Failed: $failed");
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



