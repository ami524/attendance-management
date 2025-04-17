<?php
//管理者＿勤怠一覧画面＿日ごとの勤怠情報を表示
?>

@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/attendance/index.css') }}" />
@endsection

@section('content')
<div class="container">
    <h1>{{ $date->format('Y年n月j日') }}の勤怠</h1>

    <div class="date-navigation">
        <a href="{{ route('admin.attendance.list', ['date' => $date->copy()->subDay()->toDateString()]) }}">← 前日</a>
        <span>{{ $date->format('Y年m月d日') }}</span>
        <a href="{{ route('admin.attendance.list', ['date' => $date->copy()->addDay()->toDateString()]) }}">翌日 →</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>名前</th>
                <th>出勤</th>
                <th>退勤</th>
                <th>休憩</th>
                <th>合計</th>
                <th>詳細</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($attendances as $attendance)
                <tr>
                    <td>{{ $attendance->user->name }}</td>
                    <td>{{ optional($attendance->clock_in)->format('H:i') ?? '-' }}</td>
                    <td>{{ optional($attendance->clock_out)->format('H:i') ?? '-' }}</td>
                    <td>{{ $attendance->break_time ?? '-' }}</td>
                    <td>{{ $attendance->total_work_time ?? '-' }}</td>
                    <td><a href="{{ url('/attendance/' . $attendance->id) }}">詳細</a></td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">この日に出勤したユーザーはいません。</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection