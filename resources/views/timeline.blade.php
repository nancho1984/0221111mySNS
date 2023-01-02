<x-app-layout>
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
            <!--検索フォーム　あとでナビバーに-->
            <form method="GET" action="{{ route('searchbar_posts') }}">
                <input type="search" placeholder="コーデを検索" name="search_posts" value="@if (isset($search)) {{ $search }} @endif">
                <div>
                    <button type="submit">検索</button>
                </div>
            </form>
            
            @auth
                <a href="/posts/create">新規投稿</a>
                <a href="/users/ {{$auth_user->id}}">マイページ</a>
            @else
            <!--ログインして「ない」とき-->
                <a href="/register">新規登録</a>
                <a href="/login">ログイン</a>
            @endauth
            
            <div class="bg-white py-6 sm:py-8 lg:py-12">
                <div class="max-w-screen-2xl px-8 md:px-8 mx-auto">
                    <div class="flex flex-wrap grid grid-cols-2 ">
                        
                        @auth
                            <h2>フォロー新着</h2>
                            <div name="follows_new_posts">
                                @foreach($follows_new_posts as $follows_new_post)
                                    <x-post :post="$follows_new_post" />
                                @endforeach
                            </div>
                        @endauth
                        
                        <h2>みんなの新着</h2>
                        <div name="new_posts">
                            @foreach($new_posts as $new_post)
                                <x-post :post="$new_post" />
                            @endforeach
                        </div>
                
                        <h2>人気の投稿</h2>
                        <div name="popular_posts">
                            @foreach($popular_posts as $popular_post)
                                <x-post :post="$popular_post" />
                            @endforeach
                        </div>
                        
                    </div>
                </div>
            </div>
            
        </body>
    </html>
</x-app-layout>