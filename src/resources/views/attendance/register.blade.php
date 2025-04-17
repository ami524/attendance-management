<?php
//一般＿出勤登録画面＿出勤、休憩、退勤の打刻
?>

@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance/checkin.css') }}" />
@endsection

@section('content')

<div class="container">
    <h2>勤怠登録</h2>

    <!-- 勤怠状態の表示 -->
    <div class="attendance-status">
        <p>現在の勤怠状態: {{ $attendanceStatus }}</p>
    </div>

    <div class="today">
        <p>本日: {{ $today }}</p>
    </div>

    <div class="current-time">
        <p>現在の時間: {{ $currentTime }}</p>
    </div>

    <!-- 勤怠状態によって表示を変更 -->
    <div class="attendance-actions">
        @if ($attendanceStatus == '勤務外')
            <form action="{{ route('attendance.checkin') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">出勤</button>
            </form>
        @elseif ($attendanceStatus == '出勤中')
            <form action="{{ route('attendance.checkout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger">退勤</button>
            </form>
            <form action="{{ route('attendance.break') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-warning">休憩入</button>
            </form>
        @elseif ($attendanceStatus == '休憩中')
            <form action="{{ route('attendance.breakReturn') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success">休憩戻</button>
            </form>
        @elseif ($attendanceStatus == '退勤済')
            <p>お疲れさまでした。</p>
        @endif
    </div>
</div>

<script>
    function updateTime() {
        document.getElementById('current-time').innerText = new Date().toLocaleTimeString('ja-JP');
    }
    setInterval(updateTime, 1000);
</script>

@endsection
