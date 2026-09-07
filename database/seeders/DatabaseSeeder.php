<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['super_admin', 'lms_admin', 'principal_admin', 'learner'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        $admin = User::firstOrCreate(
            ['email' => 'admin@esa.com'],
            [
                'nik' => '9999999999999999',
                'name' => 'Super Administrator',
                'password' => Hash::make('password123'),
            ]
        );
        $admin->assignRole('super_admin');
    }
}
