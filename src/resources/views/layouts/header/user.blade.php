<?php
//一般ログイン状態のヘッダー
?>

<header>
    <nav>
        <ul>
            <li><a href="{{ route('attendance.checkin') }}">勤怠</a></li>
            <li><a href="{{ route('attendance.index') }}">勤怠一覧</a></li>
            <li><a href="{{ route('requests.index') }}">申請</a></li>
            <li>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit">ログアウト</button>
                </form>
            </li>
        </ul>
    </nav>
</header>