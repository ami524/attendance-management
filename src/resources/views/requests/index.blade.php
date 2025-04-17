<?php
//一般＿申請一覧画面
?>

@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance/show.css') }}">
@endsection

@section('content')
<div class="container">
    <h2 class="text-2xl font-bold mb-4">申請一覧</h2>

    <div class="mb-4">
        <a href="{{ route('requests.index', ['status' => 'pending']) }}"
            class="{{ $status === 'pending' ? 'font-bold underline' : '' }} mr-4">承認待ち</a>
        <a href="{{ route('requests.index', ['status' => 'approved']) }}"
            class="{{ $status === 'approved' ? 'font-bold underline' : '' }}">承認済み</a>
    </div>

    @if($requests->isEmpty())
        <p>表示する申請がありません。</p>
    @else
    <table class="table-auto w-full border">
        <thead>
            <tr class="bg-gray-200">
                <th class="px-4 py-2 border">状態</th>
                <th class="px-4 py-2 border">名前</th>
                <th class="px-4 py-2 border">対象日時</th>
                <th class="px-4 py-2 border">申請理由</th>
                <th class="px-4 py-2 border">申請日時</th>
                <th class="px-4 py-2 border">詳細</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requests as $request)
            <tr>
                <td class="px-4 py-2 border">{{ $request->status === 'pending' ? '承認待ち' : '承認済み' }}</td>
                <td class="px-4 py-2 border">{{ $request->user->name }}</td>
                <td class="px-4 py-2 border">{{ $request->attendance->work_date->format('Y/m/d') }}</td>
                <td class="px-4 py-2 border">{{ $request->reason }}</td>
                <td class="px-4 py-2 border">{{ $request->created_at->format('Y/m/d') }}</td>
                <td class="px-4 py-2 border">
                    <a href="{{ route('attendance.show', ['id' => $request->attendance->id]) }}" class="text-blue-600 underline">詳細</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection
