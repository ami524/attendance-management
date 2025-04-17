<?php
//一般＿勤怠一覧画面＿月ごとの勤怠情報を表示
?>

@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance/index.css') }}" />
@endsection

@section('content')
<div class="container">
    <h2>勤怠一覧</h2>

    <!-- 月の切り替え -->
    <div class="d-flex justify-content-between align-items-center my-3">
        <a href="{{ route('attendance.list', ['month' => $currentMonth->copy()->subMonth()->format('Y-m')]) }}" class="btn btn-primary">前月</a>
        <h4>{{ $currentMonth->format('Y年m月') }}</h4>
        <a href="{{ route('attendance.list', ['month' => $currentMonth->copy()->addMonth()->format('Y-m')]) }}" class="btn btn-primary">翌月</a>
    </div>

    <!-- 勤怠一覧 -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>日付</th>
                <th>出勤時刻</th>
                <th>退勤時刻</th>
                <th>休憩時間</th>
                <th>勤務時間</th>
                <th>詳細</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($attendances as $attendance)
                <tr>
                    <td>{{ $attendance->created_at->format('m/d (D)') }}</td>
                    <td>{{ optional($attendance->started_at)->format('H:i') ?? '--:--' }}</td>
                    <td>{{ optional($attendance->ended_at)->format('H:i') ?? '--:--' }}</td>
                    <td>{{ gmdate('H:i', strtotime($attendance->break_ended_at) - strtotime($attendance->break_started_at)) }}</td>
                    <td>{{ gmdate('H:i', strtotime($attendance->ended_at) - strtotime($attendance->started_at) - (strtotime($attendance->break_ended_at) - strtotime($attendance->break_started_at))) }}</td>
                    <td><a href="{{ route('attendance.show', ['id' => $attendance->id]) }}" class="btn btn-info">詳細</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
