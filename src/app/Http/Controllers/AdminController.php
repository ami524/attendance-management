<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class AdminController extends Controller
{
    public function attendanceIndex() {
        return view('admin.attendance.index');
    }

    public function attendanceShow($id) {
        $attendance = Attendance::with('user')->findOrFail($id);
        return view('admin.attendance.show', compact('attendance'));
    }

    public function staffList()
    {
        // スコープを使って一般ユーザー一覧を取得
        $staff = User::general()->get();

        return view('admin.staff.index', compact('staff'));
    }

    public function staffAttendance($id, Request $request)
    {
        $user = User::findOrFail($id);

        // 年月取得（クエリ or デフォルトで今月）
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);

        $attendances = Attendance::where('user_id', $id)
            ->whereYear('work_day', $year)
            ->whereMonth('work_day', $month)
            ->orderBy('work_day')
            ->get();

        $current = Carbon::createFromDate($year, $month, 1);
        $prev = $current->copy()->subMonth();
        $next = $current->copy()->addMonth();

        return view('admin.staff.attendance', compact('user', 'attendances', 'current', 'prev', 'next'));
    }

    public function exportCsv($id, Request $request)
    {
        $user = User::findOrFail($id);
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);

        $attendances = Attendance::where('user_id', $id)
            ->whereYear('work_day', $year)
            ->whereMonth('work_day', $month)
            ->orderBy('work_day')
            ->get();

        $csvData = [];
        $csvData[] = ['日付', '出勤', '退勤', '休憩', '合計'];

        foreach ($attendances as $attendance) {
            $csvData[] = [
                Carbon::parse($attendance->work_day)->format('m/d(D)'),
                optional($attendance->clock_in)->format('H:i') ?? '-',
                optional($attendance->clock_out)->format('H:i') ?? '-',
                $attendance->break_duration ?? '-',
                $attendance->work_duration ?? '-',
            ];
        }

        $filename = $user->name . "_{$year}_{$month}_attendance.csv";

        $handle = fopen('php://temp', 'r+');
        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }
        rewind($handle);

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}"
        ];

        return Response::stream(function () use ($handle) {
            fpassthru($handle);
        }, 200, $headers);
    }

    public function requestsIndex(Request $request)
    {
        $tab = $request->input('tab', 'pending'); // デフォルト: 承認待ち

        $requests = AttendanceRequest::with(['user', 'attendance'])
            ->when($tab === 'approved', function ($query) {
                $query->where('status', 'approved');
            }, function ($query) {
                $query->where('status', 'pending');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.requests.index', compact('requests', 'tab'));
    }

    public function requestApproval($id) {
        return view('admin.requests.approval', compact('id'));
    }


    public function attendanceList(Request $request)
    {
        $date = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();

        // 指定日で出勤しているユーザーの勤怠を取得
        $attendances = Attendance::whereDate('clock_in', $date)
            ->with('user')
            ->get();

        return view('admin.attendance.index', compact('date', 'attendances'));
    }
}
