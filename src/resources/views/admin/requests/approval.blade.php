<?php
//管理者＿修正申請承認画面
?>

@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/requests/approval.css') }}" />
@endsection

@section('content')
<div class="approval-container">
    <h1>勤怠詳細</h1>

    <div class="request-info">
        <p><strong>名前:</strong> {{ $request->user->name }}</p>
        <p><strong>日付:</strong> {{ \Carbon\Carbon::parse($request->attendance->work_day)->format('Y年n月j日') }}</p>
        <p><strong>出勤・退勤:</strong>
            {{ optional($request->attendance->start_time)->format('H:i') ?? '--:--' }} ～
            {{ optional($request->attendance->end_time)->format('H:i') ?? '--:--' }}
        </p>

        @php
            $breaks = $request->attendance->breaks ?? [];
        @endphp

        @if (isset($breaks[0]))
            <p><strong>休憩:</strong>
                {{ optional($breaks[0]->start_time)->format('H:i') ?? '--:--' }} ～
                {{ optional($breaks[0]->end_time)->format('H:i') ?? '--:--' }}
            </p>
        @endif

        @if (isset($breaks[1]))
            <p><strong>休憩２:</strong>
                {{ optional($breaks[1]->start_time)->format('H:i') ?? '--:--' }} ～
                {{ optional($breaks[1]->end_time)->format('H:i') ?? '--:--' }}
            </p>
        @endif

        <p><strong>備考:</strong> {{ $request->reason }}</p>
    </div>

    <div class="approval-actions">
        @if ($request->status === 'approved')
            <button class="approved" disabled>承認済み</button>
        @else
            <form method="POST" action="{{ route('admin.requests.approve', $request->id) }}">
                @csrf
                <button type="submit" class="approve-button">承認</button>
            </form>
        @endif
    </div>
</div>
@endsection