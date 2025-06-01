<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Attendance;
use App\Models\WorkSchedule;
use App\Models\AnnualHoliday;

class MarkAbsentEmployees extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:mark-absent-employees';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tandai karyawan yang tidak presensi hari ini sebagai absen';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $today = Carbon::today();

        $isHoliday = AnnualHoliday::whereDate('holiday_date', $today)->exists();
        if ($isHoliday) {
            $this->info('Hari ini adalah hari libur nasional. Tidak ada karyawan yang ditandai sebagai absen.');
            \Log::info('Hari ini adalah hari libur nasional. Tidak ada karyawan yang ditandai sebagai absen.', [
                'date' => $today->toDateString(),
            ]);
            return self::SUCCESS;
        }

        $workSchedule = WorkSchedule::first();
        if (!$workSchedule) {
            $this->error('Jadwal kerja tidak ditemukan. Pastikan jadwal kerja telah diatur.');
            \Log::error('Jadwal kerja tidak ditemukan. Pastikan jadwal kerja telah diatur.', [
                'date' => $today->toDateString(),
            ]);
            return self::FAILURE;
        }

        $dayOfWeek = Carbon::parse($today)->format('l');
        if (!in_array($dayOfWeek, $workSchedule->working_days)) {
            $this->info('Hari ini adalah hari libur kerja. Tidak ada karyawan yang ditandai sebagai absen.');
            \Log::info('Hari ini adalah hari libur kerja. Tidak ada karyawan yang ditandai sebagai absen.', [
                'date' => $today->toDateString(),
                'working_days' => $workSchedule->working_days,
            ]);
            return self::SUCCESS;
        }

        $absentEmployees = User::whereDoesntHave('attendances', function ($query) use ($today) {
            $query->whereDate('date', $today);
        })->get();

        foreach ($absentEmployees as $employee) {
            Attendance::create([
                'user_id' => $employee->id,
                'date' => $today,
                'type' => 'absent',
            ]);
        }
        // kirim info ke log laravel
        \Log::info('Karyawan yang tidak presensi hari ini telah ditandai sebagai absen.', [
            'date' => $today->toDateString(),
            'absent_count' => $absentEmployees->count(),
            'total_employees' => User::count(),
        ]);
        // kirim info ke log sistem
        \Log::info('Perintah app:mark-absent-employees telah dijalankan.', [
            'date' => Carbon::now()->toDateTimeString(),
            'absent_count' => $absentEmployees->count(),
            'total_employees' => User::count(),
        ]);
        $this->info('Karyawan yang tidak presensi hari ini telah ditandai sebagai absen.');
        $this->line('Jumlah karyawan yang ditandai sebagai absen: ' . $absentEmployees->count());
        $this->line('Tanggal: ' . $today->toDateString());
        $this->line('Waktu: ' . Carbon::now()->toTimeString());
        $this->line('Total karyawan: ' . User::count());
        $this->line('Total karyawan yang absen hari ini: ' . $absentEmployees->count());
        $this->line('Total karyawan yang hadir hari ini: ' . (User::count() - $absentEmployees->count()));
        $this->line('Perintah ini dijalankan pada: ' . Carbon::now()->toDateTimeString());

        return self::SUCCESS;
    }
}
