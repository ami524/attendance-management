<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // 管理者ユーザー
        User::create([
            'name' => '管理者ユーザー',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role_id' => 1,
        ]);

        // 一般ユーザー10名
        for ($i = 1; $i <= 10; $i++) {
            \App\Models\User::create([
                'name' => "一般ユーザー{$i}",
                'email' => "user{$i}@example.com",
                'password' => Hash::make('password'),
                'role_id' => 2,
            ]);
        }
    }
}
