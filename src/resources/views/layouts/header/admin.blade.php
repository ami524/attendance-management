<?php
//管理者ログイン状態のヘッダー
?>

<header>
    <nav>
        <ul>
            <li><a href="{{ route('admin.attendance.index') }}">勤怠一覧</a></li>
            <li><a href="{{ route('admin.staff.index') }}">スタッフ一覧</a></li>
            <li><a href="{{ route('admin.requests.index') }}">申請一覧</a></li>
            <li>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit">ログアウト</button>
                </form>
            </li>
        </ul>
    </nav>
</header>
