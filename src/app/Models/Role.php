<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['role_name'];

    // リレーション：この役職を持つユーザー
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
