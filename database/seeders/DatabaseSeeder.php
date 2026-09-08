<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Setup Roles
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $lmsAdmin = Role::firstOrCreate(['name' => 'lms_admin']);
        $principalAdmin = Role::firstOrCreate(['name' => 'principal_admin']);
        $learner = Role::firstOrCreate(['name' => 'learner']);

        // Setup User
        $adminUser = User::firstOrCreate([
            'email' => 'admin@example.com'
        ], [
            'name' => 'Super Admin',
            'nik' => '0000000000000000',
            'password' => Hash::make('password123')
        ]);
        if (!$adminUser->hasRole('super_admin')) {
            $adminUser->assignRole('super_admin');
        }

        // Dummy Data
        $entity = \App\Models\Entity::firstOrCreate(['name' => 'AMK']);
        $principal1 = \App\Models\Principal::firstOrCreate(['entity_id' => $entity->id, 'name' => 'PT A'], ['code' => 'PTA']);
        $principal2 = \App\Models\Principal::firstOrCreate(['entity_id' => $entity->id, 'name' => 'PT B'], ['code' => 'PTB']);

        $region = \App\Models\Region::firstOrCreate(['name' => 'Region 4']);
        $area1 = \App\Models\Area::firstOrCreate(['region_id' => $region->id, 'name' => 'Surabaya']);
        $area2 = \App\Models\Area::firstOrCreate(['region_id' => $region->id, 'name' => 'Gresik']);
        $area3 = \App\Models\Area::firstOrCreate(['region_id' => $region->id, 'name' => 'Kediri']);

        \App\Models\Position::firstOrCreate(['name' => 'Staff IT'], ['code' => 'IT']);
        \App\Models\Department::firstOrCreate(['name' => 'Teknologi Informasi'], ['code' => 'TI']);
    }
}
