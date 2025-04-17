<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);

        //一般ユーザーの会員登録画面
        Fortify::registerView(function () {
            return view('auth.register');
        });

        //ログイン画面を切り替える
        Fortify::loginView(function (Request $request) {
            // URLに `admin` を含んでいたら管理者ログイン画面を表示
            if ($request->is('admin/*')) {
                return view('admin.auth.login');
            }
            return view('auth.login');// 一般ユーザーのログイン画面
        });

        //ログイン時のリダイレクト先を変更
        Fortify::authenticateUsing(function (Request $request) {
            $user = Auth::attempt([
                'email' => $request->email,
                'password' => $request->password,
            ]);

            if ($user) {
                // **管理者なら管理者用勤怠一覧画面にリダイレクト**
                if (Auth::user()->role === 'admin') {
                    return redirect()->intended('/admin/attendance/index');
                }
                // **一般ユーザーなら打刻画面にリダイレクト**
                return redirect()->intended('/attendance/checkin');
            }

            return null;
        });

    }
}
