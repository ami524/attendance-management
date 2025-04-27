<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'work_date', 'status', 'clock_in', 'clock_out', 'break_start', 'break_end'
    ];

    protected $dates = [
        'clock_in' => 'datetime',
        'break_start' => 'datetime',
        'break_end' => 'datetime',
        'clock_out' => 'datetime',
        'work_date' => 'date',
    ];



    public function requests()
    {
        return $this->hasMany(AttendanceRequest::class);
    }

    /**
     * 勤務時間（休憩時間を除いた合計時間）を計算
     */
    public function getTotalWorkTimeAttribute()
    {
        if (!$this->clock_in || !$this->clock_out) {
            return null;
        }

        $workTime = $this->clock_out->diffInSeconds($this->clock_in);
        $breakTime = ($this->break_start && $this->break_end)
            ? $this->break_end->diffInSeconds($this->break_start)
            : 0;

        return gmdate('H:i', max(0, $workTime - $breakTime));
    }

    /**
     * 休憩時間を計算
     */
    public function getBreakTimeAttribute()
    {
        if (!$this->break_start || !$this->break_end) {
            return null;
        }

        return gmdate('H:i', $this->break_end->diffInSeconds($this->break_start));
    }

    /**
     * 日付をフォーマットして取得（例：2025/04/14）
     */
    public function getFormattedDateAttribute()
    {
        return $this->work_date ? \Carbon\Carbon::parse($this->work_date)->format('Y/m/d') : '';
    }

    /**
     * この勤怠がどのユーザーに紐づくか
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * この勤怠に対する修正申請
     */
    public function attendanceRequests()
    {
        return $this->hasMany(AttendanceRequest::class);
    }


    public function intervals()
    {
        return $this->hasMany(Interval::class);
    }

}
