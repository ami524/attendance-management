<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $users = User::where('role_id', 2)->get();
        $startDate = Carbon::now()->subMonths(2)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        foreach ($users as $user) {
            $date = $startDate->copy();
            while ($date <= $endDate) {
                if ($date->isWeekend()) {
                    $date->addDay();
                    continue;
                }
                //土日を含めるのならif ($date->isWeekend()) の部分を削除

                $clockIn = Carbon::createFromTime(9, 0, 0);
                $breakStart = Carbon::createFromTime(12, 0, 0);
                $breakEnd = Carbon::createFromTime(13, 0, 0);
                $clockOut = Carbon::createFromTime(18, 0, 0);

                Attendance::create([
                    'user_id' => $user->id,
                    'work_date' => $date->toDateString(),
                    'clock_in' => $clockIn,
                    'break_start' => $breakStart,
                    'break_end' => $breakEnd,
                    'clock_out' => $clockOut,
                    'status' => 'completed',
                ]);

                $date->addDay();
            }
        }
    }
}
