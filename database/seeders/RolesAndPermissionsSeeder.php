<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Role::firstOrCreate(['name' => 'super_admin']);
        Role::firstOrCreate(['name' => 'lms_admin']);
        Role::firstOrCreate(['name' => 'principal_admin']);
        Role::firstOrCreate(['name' => 'learner']);

        $user = User::firstOrCreate(
            ['email' => 'admin@esalms.com'],
            [
                'nik' => '1234567890',
                'name' => 'Super Administrator',
                'password' => bcrypt('password'),
                'status' => 'ACTIVE'
            ]
        );

        $user->assignRole('super_admin');
    }
}
