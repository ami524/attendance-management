<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'year', 'month', 'total_work_hours', 'total_break_hours', 'total_overtime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
