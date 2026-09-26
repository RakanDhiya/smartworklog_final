<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Daftar permission per modul, mengacu ke Authorization Matrix Phase 1.
     * Format: "modul.aksi"
     */
    protected array $permissions = [
        // Clients
        'clients.view', 'clients.create', 'clients.update', 'clients.delete',

        // Cases
        'cases.view', 'cases.create', 'cases.update', 'cases.delete', 'cases.assign',

        // Activities
        'activities.view', 'activities.create', 'activities.update', 'activities.delete',

        // Attendance
        'attendances.view', 'attendances.manage_own', 'attendances.manage_all',

        // Tasks
        'tasks.view', 'tasks.create', 'tasks.update', 'tasks.delete',

        // Schedules
        'schedules.view', 'schedules.create', 'schedules.update', 'schedules.delete',

        // Documents
        'documents.view', 'documents.upload', 'documents.delete',

        // Reports
        'reports.view', 'reports.generate_employee', 'reports.generate_case', 'reports.generate_monthly',

        // Employees (user management)
        'employees.view', 'employees.create', 'employees.update', 'employees.delete',

        // Audit Log
        'audit_logs.view',

        // Office Settings
        'office_profile.manage',
    ];

    public function run(): void
    {
        // Reset cache permission Spatie agar seeding idempotent saat dijalankan ulang
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        foreach ($this->permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $lawyer = Role::firstOrCreate(['name' => 'lawyer', 'guard_name' => 'web']);
        $staff = Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);

        // Admin: full access ke semua permission
        $admin->syncPermissions(Permission::all());

        // Lawyer: sesuai matrix Phase 1 — scope pada case/activity/task miliknya + view report terbatas
        $lawyer->syncPermissions([
            'clients.view',
            'cases.view', 'cases.create', 'cases.update',
            'activities.view', 'activities.create', 'activities.update', 'activities.delete',
            'attendances.view', 'attendances.manage_own',
            'tasks.view', 'tasks.create', 'tasks.update',
            'schedules.view', 'schedules.create', 'schedules.update',
            'documents.view', 'documents.upload',
            'reports.view', 'reports.generate_employee', 'reports.generate_case',
        ]);

        // Staff: lebih terbatas, tidak bisa create case/assign
        $staff->syncPermissions([
            'clients.view',
            'cases.view',
            'activities.view', 'activities.create', 'activities.update',
            'attendances.view', 'attendances.manage_own',
            'tasks.view', 'tasks.update',
            'schedules.view',
            'documents.view', 'documents.upload',
            'reports.view',
        ]);

        $this->command->info('Roles & permissions seeded: admin, lawyer, staff.');
    }
}