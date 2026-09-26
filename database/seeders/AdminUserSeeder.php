<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin Kantor',
                'password' => 'password123', // dummy, HANYA untuk development
                'is_active' => true,
            ]
        );

        $admin->assignRole('admin');

        $lawyer = User::firstOrCreate(
            ['email' => 'lawyer@example.com'],
            [
                'name' => 'Lawyer Contoh',
                'password' => 'password123',
                'is_active' => true,
            ]
        );

        $lawyer->assignRole('lawyer');

        $staff = User::firstOrCreate(
            ['email' => 'staff@example.com'],
            [
                'name' => 'Staff Contoh',
                'password' => 'password123',
                'is_active' => true,
            ]
        );

        $staff->assignRole('staff');

        $this->command->info('Dummy users seeded: admin@example.com, lawyer@example.com, staff@example.com (password: password123)');
    }
}