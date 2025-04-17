<?php
//管理者＿申請一覧画面
?>

@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/requests/index.css') }}" />
@endsection

@section('content')
<div class="container">
    <h1>申請一覧</h1>

    <div class="tabs">
        <a href="{{ route('admin.requests.index', ['tab' => 'pending']) }}" class="{{ $tab === 'pending' ? 'active' : '' }}">承認待ち</a>
        <a href="{{ route('admin.requests.index', ['tab' => 'approved']) }}" class="{{ $tab === 'approved' ? 'active' : '' }}">承認済み</a>
    </div>

    <table class="request-table">
        <thead>
            <tr>
                <th>状態</th>
                <th>名前</th>
                <th>対象日時</th>
                <th>申請理由</th>
                <th>申請日時</th>
                <th>詳細</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($requests as $request)
                <tr>
                    <td>{{ $request->status === 'approved' ? '承認済み' : '承認待ち' }}</td>
                    <td>{{ $request->user->name }}</td>
                    <td>
                        {{ $request->attendance && $request->attendance->work_day
                            ? \Carbon\Carbon::parse($request->attendance->work_day)->format('Y/m/d')
                            : 'N/A'
                        }}
                    </td>
                    <td>{{ $request->reason }}</td>
                    <td>{{ \Carbon\Carbon::parse($request->created_at)->format('Y/m/d') }}</td>
                    <td><a href="#">詳細</a></td> {{-- 後で実装 --}}
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection