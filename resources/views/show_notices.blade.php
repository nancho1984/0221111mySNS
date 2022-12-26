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
        <div class='notifications'>
            @foreach ($notifications as $notification)
            <div class='notification'>
                <p class='message'>{{ $notification->message }}</p>
            </div>
            <!--通知で飛ばすURLについての考え
                「フォロー」{ユーザー名}：ユーザープロフィールに飛ばす
                「いいね」「返信」：-->
                
            <!--a href=" route 'show_notices', $user->id) }}">詳しく見る</a-->

            @endforeach
        </div>
        <div class='paginate'>
            {{ $notifications->links() }}
        </div>
    </body>
</html>