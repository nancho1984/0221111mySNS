<!DOCTYPE html>
<!--str_replace'検索文字列,'変換後','検索対象'/ app getLocaleはユーザーの言語環境-->
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Kitemite</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>
    <body>
        <h1>Kitemite</h1>
        <a href='/posts/create'>新規投稿</a>
        <p>通知一覧</p>
        <div class='notices'>
            @if($convert_notices != false)
                <!--通知があるとき-->
                @foreach ($convert_notices as $notice)
                <div class='notice'>
                    @if($notice['type'] === "replyPost" || $notice['type'] === "replyCmnt")
                        <!--投稿のコメント、コメントへの返信のとき-->
                        <span>
                            <p class='message'>{{ $notice['agents']->nickname }}さんがあなたの
                                @if($notice['type'] === "replyPost")
                                投稿にコメントしました
                                @else
                                コメントに返信しました
                                @endif
                            </p>
                        </span>
                        {{ $notice['message'] }}
                        <a href="{{route('show_post', $notice['post_id']) }}">詳しく見る</a>
                    
                    @else
                        <!--いいねかフォローの通知のとき-->
                        @if($notice['type'] === "follow")
                            <p class='message'>{{ $notice['count'] }}人にフォローされました</p>
                        @else
                            <p class='message'>{{ $notice['count'] }}人にいいねされました</p>
                            <a href="{{route('show_post', $notice['post_id']) }}">詳しく見る</a>
                        @endif
                        
                        <!--ユーザー一覧を表示-->
                        @foreach( $notice['agents'] as $agent)
                            {{ $agent->nickname }}
                        @endforeach
                        
                    @endif
                </div>
                @endforeach
            @else
                <!--通知がないとき-->
                <p>未読の通知はありません</p>
            @endif
        </div>
    </body>
</html>