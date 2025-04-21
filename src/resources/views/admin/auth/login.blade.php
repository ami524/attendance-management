<?php
//管理者＿ログイン画面
?>

@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/auth/login.css') }}" />
@endsection

@section('content')
<div class="container">
    <h2>管理者ログイン</h2>

    <form action="{{ route('admin.login') }}" method="POST">
        @csrf

        @error('email')
        <tr>
            <td>
                {{$errors->first('email')}}
            </td>
        </tr>
        @enderror

        <div class="form-group">
            <label for="email">メールアドレス</label>
            <input type="email" id="email" name="email" autofocus>
        </div>

        @error('password')
        <tr>
            <td>
                {{$errors->first('password')}}
            </td>
        </tr>
        @enderror

        <div class="form-group">
            <label for="password">パスワード</label>
            <input type="password" id="password" name="password">
        </div>

        <div>
            @error('login')
                <p style="color: red;">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit">管理者ログインする</button>
    </form>
</div>
@endsection