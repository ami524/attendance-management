<?php
//共通部
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COACHTECH勤怠管理</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" />
    @yield('css')
</head>
<body>

    {{-- ヘッダーの切り替え --}}
    @if (Auth::check())
        @if (Auth::user()->is_admin)
            @include('layouts.header.admin')
        @else
            @include('layouts.header.user')
        @endif
    @else
        @include('layouts.header.guest')
    @endif

    {{-- コンテンツ部分 --}}
    <main>
        @yield('content')
    </main>

</body>
</html>