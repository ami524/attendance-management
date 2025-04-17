<?php
//管理者＿スタッフ別勤怠一覧画面＿月ごとの勤怠情報を表示
?>

@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/staff/attendance.css') }}" />
@endsection

@section('content')
    <h1>{{ $user->name }}さんの勤怠</h1>

    <div class="month-selector">
        <a href="{{ route('admin.attendance.staff', ['id' => $user->id, 'year' => $prev->year, 'month' => $prev->month]) }}">&lt; 前月</a>
        <span>{{ $current->format('Y/m') }}</span>
        <a href="{{ route('admin.attendance.staff', ['id' => $user->id, 'year' => $next->year, 'month' => $next->month]) }}">翌月 &gt;</a>
    </div>

    <table class="attendance-table">
        <thead>
            <tr>
                <th>日付</th>
                <th>出勤</th>
                <th>退勤</th>
                <th>休憩</th>
                <th>合計</th>
                <th>詳細</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($attendances as $attendance)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($attendance->work_day)->format('m/d(D)') }}</td>
                    <td>{{ optional($attendance->clock_in)->format('H:i') ?? '-' }}</td>
                    <td>{{ optional($attendance->clock_out)->format('H:i') ?? '-' }}</td>
                    <td>{{ $attendance->break_duration ?? '-' }}</td>
                    <td>{{ $attendance->work_duration ?? '-' }}</td>
                    <td><a href="{{ route('attendance.show', ['id' => $attendance->id]) }}">詳細</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <form action="{{ route('admin.attendance.staff.csv', ['id' => $user->id, 'year' => $current->year, 'month' => $current->month]) }}" method="GET">
        <button type="submit">CSV出力</button>
    </form>
@endsection