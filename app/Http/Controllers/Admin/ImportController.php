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
                    'principal_id' => $data['principal_id'],
                    'position_id' => $data['position_id'],
                    'department_id' => $data['department_id'] ?: null,
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
}

