<?php
//一般＿会員登録画面
?>

@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/register.css') }}" />
@endsection

@section('content')

<div class="container">
    <h2>会員登録</h2>
    <form action="/register" method="POST">
        @csrf

        @error('name')
        <tr>
            <td>
                {{$errors->first('name')}}
            </td>
        </tr>
        @enderror

        <div class="form-group">
            <label for="name">名前</label>
            <input type="text" id="name" name="name" required>
        </div>

        @error('email')
        <tr>
            <td>
                {{$errors->first('email')}}
            </td>
        </tr>
        @enderror

        <div class="form-group">
            <label for="email">メールアドレス</label>
            <input type="email" id="email" name="email" required>
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
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="password_confirmation">パスワード確認</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>
        <button type="submit">登録する</button>
    </form>
    <p><a href="{{ route('login') }}">ログインはこちら</a></p>
</div>

@endsection
