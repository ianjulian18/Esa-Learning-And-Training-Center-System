<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ImportBatch;
use App\Models\ImportRow;
use App\Models\User;
use App\Models\EmploymentHistory;
use App\Models\PrincipalHistory;
use App\Models\Entity;
use App\Models\Principal;
use App\Models\Region;
use App\Models\Area;
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
        // Trim headers
        $header = array_map('trim', $header);
        return [$header, $csvData];
    }

    private function resolveReferences($data)
    {
        $entity = Entity::whereRaw('LOWER(name) = ?', [strtolower(trim($data['entity'] ?? ''))])->first();
        if (!$entity) throw new \Exception('Entity name not found: ' . ($data['entity'] ?? ''));

        $principal = Principal::where('entity_id', $entity->id)->whereRaw('LOWER(name) = ?', [strtolower(trim($data['principal'] ?? ''))])->first();
        if (!$principal) throw new \Exception('Principal name not found in Entity: ' . ($data['principal'] ?? ''));

        $regionId = null;
        if (!empty(trim($data['region'] ?? ''))) {
            $region = Region::whereRaw('LOWER(name) = ?', [strtolower(trim($data['region']))])->first();
            if ($region) $regionId = $region->id;
        }

        $areaId = null;
        if (!empty(trim($data['area'] ?? ''))) {
            if ($regionId) {
                $area = Area::where('region_id', $regionId)->whereRaw('LOWER(name) = ?', [strtolower(trim($data['area']))])->first();
            } else {
                $area = Area::whereRaw('LOWER(name) = ?', [strtolower(trim($data['area']))])->first();
            }
            if ($area) {
                $areaId = $area->id;
                $regionId = $area->region_id; // Infer region
            } else {
                throw new \Exception('Area name not found: ' . $data['area']);
            }
        }

        $position = Position::whereRaw('LOWER(name) = ?', [strtolower(trim($data['position'] ?? ''))])->first();
        if (!$position) throw new \Exception('Position name not found: ' . ($data['position'] ?? ''));

        $departmentId = null;
        if (!empty(trim($data['department'] ?? ''))) {
            $department = Department::whereRaw('LOWER(name) = ?', [strtolower(trim($data['department']))])->first();
            if ($department) $departmentId = $department->id;
        }

        return [$entity->id, $principal->id, $regionId, $areaId, $position->id, $departmentId];
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

                list($entityId, $principalId, $regionId, $areaId, $positionId, $departmentId) = $this->resolveReferences($data);

                PrincipalHistory::create([
                    'user_id' => $user->id,
                    'principal_id' => $principalId,
                    'start_date' => $data['join_date'] ?? now()->toDateString()
                ]);

                EmploymentHistory::create([
                    'user_id' => $user->id,
                    'nip' => $data['nip'],
                    'entity_id' => $entityId,
                    'principal_id' => $principalId,
                    'region_id' => $regionId,
                    'area_id' => $areaId,
                    'position_id' => $positionId,
                    'department_id' => $departmentId,
                    'start_date' => $data['join_date'] ?? now()->toDateString(),
                    'status' => 'ACTIVE',
                    'source' => 'IMPORT'
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

                $user = User::where('email', $data['email'])->orWhere('nik', $data['nik'])->first();
                if (!$user) {
                    throw new \Exception('User not found. Cannot Update.');
                }

                if (!empty($data['name'])) $user->name = $data['name'];
                if (!empty($data['password'])) $user->password = Hash::make($data['password']);
                $user->save();

                list($entityId, $principalId, $regionId, $areaId, $positionId, $departmentId) = $this->resolveReferences($data);

                // Handle Principal History
                $activePrincipal = PrincipalHistory::where('user_id', $user->id)
                    ->whereNull('end_date')
                    ->orderBy('start_date', 'desc')
                    ->first();

                if (!$activePrincipal || $activePrincipal->principal_id != $principalId) {
                    if ($activePrincipal) $activePrincipal->update(['end_date' => now()]);
                    PrincipalHistory::create([
                        'user_id' => $user->id,
                        'principal_id' => $principalId,
                        'start_date' => $data['join_date'] ?? now()->toDateString()
                    ]);
                }

                // Handle Employment History
                EmploymentHistory::where('user_id', $user->id)
                    ->where('status', 'ACTIVE')
                    ->update(['status' => 'INACTIVE', 'end_date' => now()]);

                EmploymentHistory::create([
                    'user_id' => $user->id,
                    'nip' => $data['nip'],
                    'entity_id' => $entityId,
                    'principal_id' => $principalId,
                    'region_id' => $regionId,
                    'area_id' => $areaId,
                    'position_id' => $positionId,
                    'department_id' => $departmentId,
                    'start_date' => $data['join_date'] ?? now()->toDateString(),
                    'status' => 'ACTIVE',
                    'source' => 'IMPORT'
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

        $columns = ['nik', 'name', 'email', 'nip', 'entity', 'principal', 'region', 'area', 'position', 'department', 'join_date', 'password'];
        $dummyData = ['123456789', 'John Doe', 'john@example.com', 'EMP001', 'AMK', 'PT A', 'Region 4', 'Surabaya', 'Staff IT', 'Teknologi Informasi', '2024-01-01', 'password123'];

        $callback = function() use($columns, $dummyData) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            fputcsv($file, $dummyData);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
