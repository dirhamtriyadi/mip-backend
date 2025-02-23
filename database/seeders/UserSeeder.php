<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create super admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => bcrypt('password'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $superAdmin->detail_users()->create([
            'nik' => '1234567890',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $roleSuperAdmin = Role::create(['name' => 'Super Admin']);
        $roleSuperAdmin->givePermissionTo(Permission::all());

        $superAdmin->assignRole($roleSuperAdmin);

        // Create admin bank permissions
        $roleAdminBankPermissions = [
            'laporan-penagihan.index',
            'laporan-penagihan.data-my-bank',
            'laporan-penagihan.create',
            'laporan-penagihan.edit',
            'laporan-penagihan.delete',
        ];

        // Ensure permissions exist
        foreach ($roleAdminBankPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $roleAdminBank = Permission::whereIn('name', $roleAdminBankPermissions)->get();

        // Create admin MIP
        $adminMIP = User::create([
            'name' => 'Admin MIP',
            'email' => 'adminmip@gmail.com',
            'password' => bcrypt('password'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $adminMIP->detail_users()->create([
            'nik' => '1234567891',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $roleAdminMIP = Role::create(['name' => 'Admin MIP']);
        $roleAdminMIP->givePermissionTo(Permission::all());

        $adminMIP->assignRole($roleAdminMIP);

        // Create admin HIK
        $adminHIK = User::create([
            'name' => 'Admin HIK',
            'email' => 'adminhik@gmail.com',
            'password' => bcrypt('password'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $adminHIK->detail_users()->create([
            'nik' => '1234567892',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $roleAdminHIK = Role::create(['name' => 'Admin HIK']);
        $roleAdminHIK->givePermissionTo($roleAdminBank);

        $adminHIK->assignRole($roleAdminHIK);

        // Create admin Almabrur
        $adminAlmabrur = User::create([
            'name' => 'Admin Almabrur',
            'email' => 'adminalmabrur@gmail.com',
            'password' => bcrypt('password'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $adminAlmabrur->detail_users()->create([
            'nik' => '1234567893',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $roleAdminAlmabrur = Role::create(['name' => 'Admin Almabrur']);
        $roleAdminAlmabrur->givePermissionTo($roleAdminBank);

        $adminAlmabrur->assignRole($roleAdminAlmabrur);

        // Create user
        $user = User::create([
            'name' => 'User',
            'email' => 'user@gmail.com',
            'password' => bcrypt('password'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $user->detail_users()->create([
            'nik' => '0987654321',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $roleSurveyor = Role::create(['name' => 'Surveyor']);

        $user->assignRole($roleSurveyor);

        $rolePenagih = Role::create(['name' => 'Penagih']);

        $user->assignRole($rolePenagih);
    }
}
