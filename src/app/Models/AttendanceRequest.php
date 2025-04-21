<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'attendance_id',
        'request_type',
        'before_time',
        'after_time',
        'reason',
        'status',
        'admin_id',
        'reviewed_at',
    ];

    protected $casts = [
        'before_time' => 'datetime',
        'after_time' => 'datetime',
        'reviewed_at' => 'datetime',
    ];


    /**
     * ユーザー（申請者）とのリレーション
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 勤怠情報とのリレーション
     */
    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    /**
     * 管理者ユーザーとのリレーション（nullable）
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
