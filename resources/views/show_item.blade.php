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
        <p>同じアイテムを使用した投稿</p>
        <div class='posts'>
            @foreach ($posts as $post)
            <div class='post'>
                <!--user_id、ユーザーテーブルのidで検索させた後
                    ユーザーのアカウント名を表示できるようにしたい-->
                <p class='account_id'>＠{{ $post->user->name }}</p>
                <!-- 画像のサイズ調整すること-->
                <div class='image'>
                    <img src="{{ $post->image }}">
                </div>
                <!-- bodyはテスト時寂しいから表示、本番では消すか表示文字制限する-->
                <p class='body'>{{ $post->body }}</p>
                </div>
            </div>
            <a href="/posts/{{ $post->id }}">詳しく見る</a>
            @endforeach
        </div>
        <div class='paginate'>
            {{ $posts->links() }}
        </div>
    </body>
</html>