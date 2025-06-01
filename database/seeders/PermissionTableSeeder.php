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
            'jam-kerja.index',
            'jam-kerja.all-data',
            'jam-kerja.create',
            'jam-kerja.edit',
            'jam-kerja.delete',
            'hari-libur.index',
            'hari-libur.all-data',
            'hari-libur.create',
            'hari-libur.edit',
            'hari-libur.delete',
            'bank.index',
            'bank.all-data',
            'bank.create',
            'bank.edit',
            'bank.delete',
            'nasabah.index',
            'nasabah.all-data',
            'nasabah.create',
            'nasabah.edit',
            'nasabah.delete',
            'kehadiran.index',
            'kehadiran.all-data',
            'kehadiran.create',
            'kehadiran.edit',
            'kehadiran.delete',
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
            'calon-nasabah.index',
            'calon-nasabah.all-data',
            'calon-nasabah.create',
            'calon-nasabah.edit',
            'calon-nasabah.delete',
            'survei.index',
            'survei.all-data',
            'survei.create',
            'survei.edit',
            'survei.delete',
            'laporan-petugas.index',
            'laporan-petugas.all-data',
            'laporan-petugas.create',
            'laporan-petugas.edit',
            'laporan-petugas.delete',
            'laporan-kehadiran.index',
            'laporan-kehadiran.all-data',
            'laporan-kehadiran.create',
            'laporan-kehadiran.edit',
            'laporan-kehadiran.delete',
            'laporan-penagihan.index',
            'laporan-penagihan.all-data',
            'laporan-penagihan.data-my-bank',
            'laporan-penagihan.create',
            'laporan-penagihan.edit',
            'laporan-penagihan.delete',
            'laporan-survei.index',
            'laporan-survei.all-data',
            'laporan-survei.data-my-bank',
            'laporan-survei.create',
            'laporan-survei.edit',
            'laporan-survei.delete',
            'role.index',
            'role.all-data',
            'role.create',
            'role.edit',
            'role.delete',
            'user.index',
            'user.all-data',
            'user.create',
            'user.edit',
            'user.delete'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
