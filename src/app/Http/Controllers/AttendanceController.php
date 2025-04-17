<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\AttendanceRequest;
use App\Http\Requests\ShowRequest;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    // 勤怠一覧画面を表示（一般ユーザー）
    public function list(Request $request)
    {
        $user = Auth::user();
        $currentMonth = Carbon::parse($request->query('month', now()->format('Y-m')));

        // 指定された月の勤怠データを取得
        $attendances = Attendance::where('user_id', $user->id)
            ->whereYear('created_at', $currentMonth->year)
            ->whereMonth('created_at', $currentMonth->month)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('attendance.index', [
            'attendances' => $attendances,
            'currentMonth' => $currentMonth,
        ]);
    }



    public function register()
    {
        $user = Auth::user();
        $now = Carbon::now();
        $today = Carbon::today();

        // 今日の勤怠データを取得
    $attendance = Attendance::where('user_id', $user->id)
        ->whereDate('created_at', $today)
        ->first();

        // 現在の勤怠状態の判定
        if (!$attendance) {
            $status = '勤務外';
        } elseif ($attendance->ended_at) {
            $status = '退勤済';
        } elseif ($attendance->break_started_at && !$attendance->break_ended_at) {
            $status = '休憩中';
        } else {
            $status = '出勤中';
        }

        // 今日の日付と現在の時刻
        $today = $now->format('Y年m月d日 (D)');
        $currentTime = $now->format('H:i');

        // 打刻画面を表示
        return view('attendance.checkin', compact('status', 'todayFormatted', 'currentTime'));
    }


    public function checkin(Request $request)
    {
        $user = Auth::user();

        // 出勤登録
        Attendance::create([
            'user_id' => $user->id,
            'status' => '出勤中',
            'started_at' => now(),
        ]);

        return redirect()->route('attendance.checkin');
    }


    public function checkout(Request $request)
    {
        $user = Auth::user();

        // 最新の勤怠記録を更新（退勤処理）
        $attendance = Attendance::where('user_id', $user->id)->latest()->first();
        if ($attendance) {
            $attendance->update([
                'status' => '退勤済',
                'ended_at' => now(),
            ]);
        }

        return redirect()->route('attendance.checkin');
    }


    public function break(Request $request)
    {
        $user = Auth::user();

        // 最新の勤怠記録を更新（休憩開始）
        $attendance = Attendance::where('user_id', $user->id)->latest()->first();
        if ($attendance) {
            $attendance->update([
                'status' => '休憩中',
                'break_started_at' => now(),
            ]);
        }

        return redirect()->route('attendance.checkin');
    }


    public function breakReturn(Request $request)
    {
        $user = Auth::user();

        // 最新の勤怠記録を更新（休憩終了）
        $attendance = Attendance::where('user_id', $user->id)->latest()->first();
        if ($attendance) {
            $attendance->update([
                'status' => '出勤中',
                'break_ended_at' => now(),
            ]);
        }

        return redirect()->route('attendance.checkin');
    }



    // 勤怠詳細画面
    public function show($id)
    {
        $attendance = Attendance::findOrFail($id);

        // 申請がすでにあるか確認
    $attendanceRequest = AttendanceRequest::where('attendance_id', $attendance->id)
        ->where('status', 'pending')
        ->first();

        return view('attendance.show', compact('attendance'));
    }


    // 勤怠修正申請
    public function update(ShowRequest $request, $id)
    {
        $attendance = Attendance::findOrFail($id);

        // 修正申請の前に変更内容を確認
        $beforeClockIn = $attendance->clock_in;
        $beforeClockOut = $attendance->clock_out;
        $beforeBreakStart = $attendance->break_start;
        $beforeBreakEnd = $attendance->break_end;
        $beforeNote = $attendance->note;

        // フォームから送信されたデータで勤怠を更新
        $attendance->update([
            'clock_in' => $request->clock_in,
            'clock_out' => $request->clock_out,
            'break_start' => $request->break_start,
            'break_end' => $request->break_end,
            'note' => $request->note,
        ]);

        // 修正申請を作成
        AttendanceRequest::create([
            'user_id' => $attendance->user_id,
            'attendance_id' => $attendance->id,
            'request_type' => 'time_change', // 修正内容が時間変更であることを示す
            'before_time' => $beforeClockIn . ' - ' . $beforeClockOut, // 変更前の出勤・退勤時間
            'after_time' => $request->clock_in . ' - ' . $request->clock_out, // 変更後の出勤・退勤時間
            'reason' => $request->note,
            'status' => 'pending', // 状態は最初は保留
            'admin_id' => null, // 管理者が承認するまでnull
            'reviewed_at' => null, // 管理者が確認するまでnull
        ]);

        // 修正申請を管理者に送信
        // ここでは仮の処理としてログ出力を行います
        \Log::info('修正申請が送信されました。: ' . $attendance->id);

        // リダイレクト
        return redirect()->route('attendance.index')->with('status', '修正申請が送信されました');
    }
}
