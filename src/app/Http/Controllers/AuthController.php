<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\AdminLoginRequest;

use App\Models\User;

class AuthController extends Controller
{
    //会員登録時の処理
        public function store(RegisterRequest $request)
    {
        $contact = $request->only(['name', 'email', 'password']);
        // パスワードをハッシュ化
        $userData['password'] = bcrypt($userData['password']);
        User::create($userData);

        // ログイン画面にリダイレクト
        return redirect()->route('login');
    }


    //ログイン処理（一般）
    public function userLogin(LoginRequest $request)
    {
        // 入力された email と password を取得
        $credentials = $request->only('email', 'password');

        // 認証試行
        if (Auth::attempt($credentials)) {
            // 認証成功時、勤怠登録画面へリダイレクト
            return redirect()->route('attendance.checkin');
        }

        // 認証失敗時
        return back()->withErrors([
            'login' => 'ログイン情報が登録されていません',
        ])->withInput($request->only('email'));
    }

    //ログイン後の処理
    public function index()
        {
            return view('attendance.register'); // 出勤登録画面へ遷移
        }


        //ログイン処理（管理者）
        public function adminLogin(AdminLoginRequest $request)
    {
        // 入力された email と password を取得
        $credentials = $request->only('email', 'password');

        // 認証試行（管理者のみ）
        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect()->route('admin.dashboard'); // 管理者用のダッシュボードにリダイレクト
        }

        // 認証失敗時
        return back()->withErrors([
            'login' => 'ログイン情報が登録されていません',
        ])->withInput($request->only('email'));
    }
}