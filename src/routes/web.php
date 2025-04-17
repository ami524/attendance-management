<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\BreakController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\AdminController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



// 一般ログインページ
Route::get('/login', function () {
    return view('auth.login');
})->name('login');
// 一般ログイン処理
Route::post('/login', [AuthController::class, 'userLogin'])->name('login');
// 一般ログイン後
Route::middleware('auth')->group(function () {
        Route::get('/attendance', [AttendanceController::class, 'register'])->name('attendance.register');
    });


// 管理者ログインページ
Route::get('/admin/login', function () {
    return view('admin.auth.login');
})->name('admin.login');
// 管理者ログイン処理
Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login');



Route::middleware(['auth'])->group(function () {
    //勤怠登録画面
    Route::get('/attendance', [AttendanceController::class, 'register'])->name('attendance.register');
    //出勤
    Route::post('/attendance/checkin', [AttendanceController::class, 'checkin'])->name('attendance.checkin');
    //退勤
    Route::post('/attendance/checkout', [AttendanceController::class, 'checkout'])->name('attendance.checkout');
    //休憩開始
    Route::post('/attendance/break', [AttendanceController::class, 'break'])->name('attendance.break');
    //休憩終了
    Route::post('/attendance/break-return', [AttendanceController::class, 'breakReturn'])->name('attendance.breakReturn');
});



// 勤怠一覧画面（一般）
Route::middleware('auth')->group(function () {
    Route::get('/attendance/list', [AttendanceController::class, 'list'])->name('attendance.list');
});


// 勤怠詳細画面（一般）
Route::middleware(['auth'])->group(function () {
    Route::get('/attendance/{id}', [AttendanceController::class, 'show'])->name('attendance.show');
    Route::put('/attendance/{id}', [AttendanceController::class, 'update'])->name('attendance.update');
});


//申請一覧画面（一般）
Route::middleware(['auth'])->group(function () {
    Route::get('/stamp_correction_request/list', [RequestController::class, 'index'])->name('requests.index');
});




//勤怠一覧画面（管理者）
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/attendance/list', [AdminController::class, 'attendanceList'])->name('admin.attendance.list');
});


//スタッフ一覧（管理者）
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/staff/list', [AdminController::class, 'staffList'])->name('admin.staff.list');
});


// スタッフ別勤怠一覧（管理者）
Route::get('/admin/attendance/staff/{id}', [AdminController::class, 'staffAttendance'])->name('admin.attendance.staff');
// CSV出力
Route::get('/admin/attendance/staff/{id}/export', [AdminController::class, 'exportCsv'])->name('admin.attendance.staff.export');


//申請一覧画面（管理者）
Route::prefix('admin')->middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/stamp_correction_request/list', [AdminController::class, 'requestsIndex'])->name('admin.requests.index');
});


// 修正申請承認画面（表示 & 承認処理）
Route::get('/stamp_correction_request/approve/{attendanceRequest}', [App\Http\Controllers\Admin\RequestController::class, 'approvalView'])->name('admin.requests.approval');
Route::post('/stamp_correction_request/approve/{attendanceRequest}', [App\Http\Controllers\Admin\RequestController::class, 'approve'])->name('admin.requests.approve');
