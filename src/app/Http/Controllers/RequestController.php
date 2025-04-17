<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AttendanceRequest;
use Carbon\Carbon;

class RequestController extends Controller
{
    //修正申請一覧表示（承認待ち／承認済み）
    public function index(Request $request) {
        $tab = $request->query('tab', 'pending'); // デフォルトは承認待ち

        $requests = AttendanceRequest::with('user', 'attendance')
            ->where('status', $tab)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.requests.index', compact('requests', 'tab'));
    }

    public function store(Request $request) {
        // 修正申請の処理
        return back()->with('status', '修正申請を送信しました');
    }

    //承認画面表示
    public function approvalView(AttendanceRequest $attendanceRequest)
    {
        $attendanceRequest->load(['user', 'attendance.breaks']);

        return view('admin.requests.approval', [
            'request' => $attendanceRequest,
        ]);
    }

    public function approve(AttendanceRequest $attendanceRequest)
    {
        if ($attendanceRequest->status === 'approved') {
            return redirect()->back()->with('message', 'すでに承認されています。');
        }

        $attendanceRequest->status = 'approved';
        $attendanceRequest->reviewed_at = now();
        $attendanceRequest->admin_id = auth()->id();
        $attendanceRequest->save();

        return redirect()->route('admin.requests.index')->with('message', '申請を承認しました。');
    }
}
