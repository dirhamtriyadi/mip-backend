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
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $admin->detail_users()->create([
            'nik' => '1234567890',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $roleAdminWeb = Role::create(['name' => 'Admin Web', 'guard_name' => 'web']);
        $roleAdminWeb->givePermissionTo(Permission::where('guard_name', 'web')->get());

        $roleAdminWebApi = Role::create(['name' => 'Admin Api', 'guard_name' => 'api']);
        $roleAdminWebApi->givePermissionTo(Permission::where('guard_name', 'api')->get());

        $admin->assignRole($roleAdminWeb);
        $admin->assignRole($roleAdminWebApi);

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
    }
}
