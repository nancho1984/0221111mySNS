<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TimelineController extends Controller
{
    //ルーティングまだやってない
    public function showTimelinePage()
    {
        return view('auth.timeline'); // resource/views/auth/timeline.blade.phpを表示する
    }

    public function postPost(Request $request) //ここはあとで実装します。(Requestはpostリクエストを取得するためのものです。)
    {

    }
}
