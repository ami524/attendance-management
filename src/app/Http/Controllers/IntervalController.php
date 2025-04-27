<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IntervalController extends Controller
{
    public function start(Request $request) {
        // 休憩開始処理
        return back()->with('status', '休憩を開始しました');
    }

    public function end(Request $request) {
        // 休憩終了処理
        return back()->with('status', '休憩を終了しました');
    }
}
