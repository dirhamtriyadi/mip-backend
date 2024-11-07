<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'absen.index',
            'absen.all-data',
            'absen.create',
            'absen.edit',
            'absen.delete',
            'laporan-absen.index',
            'laporan-absen.all-data',
            'laporan-absen.create',
            'laporan-absen.edit',
            'laporan-absen.delete',
            'cuti.index',
            'cuti.all-data',
            'cuti.create',
            'cuti.edit',
            'cuti.delete',
            'penagihan.index',
            'penagihan.all-data',
            'penagihan.create',
            'penagihan.edit',
            'penagihan.delete',
            'laporan-penagihan.index',
            'laporan-penagihan.all-data',
            'laporan-penagihan.create',
            'laporan-penagihan.edit',
            'laporan-penagihan.delete'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
