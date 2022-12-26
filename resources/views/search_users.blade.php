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
        
        <!--検索フォーム-->
        <form method="GET" action="{{ route('searchbar_posts') }}">
            <input type="search" placeholder="コーデを検索" name="search_posts" value="@if (isset($search)) {{ $search }} @endif">
            <div>
                <button type="submit">検索</button>
            </div>
        </form>
        
        <form method="GET" action="{{ route('searchbar_users') }}">
            <input type="search" placeholder="ユーザーを検索" name="search_users" value="@if (isset($search)) {{ $search }} @endif">
            <div>
                <button type="submit">検索</button>
            </div>
        </form>
        
        <div class='users'>
            <h2>「{{ $searchPhrase }}」のユーザーの検索結果</h2>
            @foreach ($users as $user)
            <div class='user'>
                <a href="/users/{{ $user->id }}">{{ $user->nickname }}</a>
                <!-- 画像のサイズ調整すること-->
                <div class='image'>
                    <img src="{{ $user->image }}">
                </div>
            </div>
            @endforeach
            
        
        <div class='paginate'>
            
            <!--下のようにページネーターを書くと
            ページネートで次ページに遷移しても、検索結果を保持するらしい-->
            @if(count($users)>0)
                {{ $users->appends(request()->input())->links() }}
            @else
                <p>{{ $searchPhrase}}の検索結果はありません</p>
            @endif
        </div>
    </body>
</html>