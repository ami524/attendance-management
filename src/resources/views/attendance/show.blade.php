<?php
//一般＿勤怠詳細画面
?>

@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance/show.css') }}">
@endsection

@section('content')
    <div class="attendance-details">
        <h1>勤怠詳細</h1>


        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <!-- 承認待ちの場合 -->
        @if ($attendanceRequest && $attendanceRequest->status === 'pending')
            <div class="alert alert-warning">
                *承認待ちのため修正はできません。
            </div>
        @else


        <form action="{{ route('attendance.update', $attendance->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">名前:</label>
                <input type="text" id="name" name="name" value="{{ $attendance->user->name }}" disabled>
            </div>

            <div class="form-group">
                <label for="date">日付:</label>
                <input type="text" id="date" name="date" value="{{ $attendance->work_date->format('Y年m月d日') }}" disabled>
            </div>

            <div class="form-group">
                <label for="clock_in">出勤時間:</label>
                <input type="time" id="clock_in" name="clock_in" value="{{ $attendance->clock_in }}">
            </div>

            <div class="form-group">
                <label for="clock_out">退勤時間:</label>
                <input type="time" id="clock_out" name="clock_out" value="{{ $attendance->clock_out }}">
            </div>

            <div class="form-group">
                <label for="break_start">休憩開始:</label>
                <input type="time" id="break_start" name="break_start" value="{{ $attendance->break_start }}">
            </div>

            <div class="form-group">
                <label for="break_end">休憩終了:</label>
                <input type="time" id="break_end" name="break_end" value="{{ $attendance->break_end }}">
            </div>

            <div class="form-group">
                <label for="note">備考:</label>
                <textarea id="note" name="note">{{ $attendance->note }}</textarea>
            </div>

            <button type="submit">修正</button>
        </form>
    </div>
@endsection
