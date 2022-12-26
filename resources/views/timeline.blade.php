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
        
        <!--通知機能仮置き、完成品ではヘッダーにだしてほしい
            ログインしてるときのみ表示-->
        @if($user)
            <div class='notification'>
                <p>{{ $number_notices }}件の通知があります</p>
                <a href="{{ route('show_notices', $user->id) }}">詳しく見る</a>
            </div>
        @endif
        
        @if($user)
        <!--ログインして「いる」とき-->
            <a href="/posts/create">新規投稿</a>
            <a href="/users/ {{$user->id}}">マイページ</a>
        @else
        <!--ログインして「ない」とき-->
            <a href="/register">新規登録</a>
            <a href="/login">ログイン</a>
        @endif
        
        <div class='new_posts'>
            <h2>新着</h2>
            @foreach ($new_posts as $new_post)
            <div class='new_post'>
                <a href="/users/{{ $new_post->user->id }}">{{ $new_post->user->nickname }}</a>
                <!-- 画像のサイズ調整すること-->
                <div class='image'>
                    <img src="{{ $new_post->image }}">
                </div>
            </div>
            
            <!--likeボタン-->
            <span>
                <!-- もし$likeがあれば＝ユーザーが「いいね」をしていたら -->
                @if($new_post->is_liked_by_auth_user())
                
                    <!-- 「いいね」取消用ボタンを表示 -->
    	            <a href="{{ route('unlike', $new_post) }}" class="btn btn-success btn-sm">
	    	            いいね
    		            <!-- 「いいね」の数を表示 -->
	                    <span class="badge">
	    		        {{ $new_post->likes->count() }}
	    	            </span>
	                </a>
                
                <!-- まだユーザーが「いいね」をしていなければ、「いいね」ボタンを表示 -->
                @else
    	            <a href="{{ route('like', $new_post) }}" class="btn btn-secondary btn-sm">
    		            いいね
    		            <!-- 「いいね」の数を表示 -->
                		<span class="badge">
    	    	    	{{ $new_post->likes->count() }}
    	              	</span>
                	</a>
                @endif
            </span>
            <a href="/posts/{{ $new_post->id }}">詳しく見る</a>
            @endforeach
        </div>
        
        <div class='paginate'>
            {{ $new_posts->links() }}
        </div>
        <script>
            function deletePost(id) {
                //use strict最新版で動かす宣言
                'use strict'

                if (confirm('削除すると復元できません。\n本当に削除しますか？')) {
                    //document:ブラウザで表示されたドキュメントを操作できる
                    document.getElementById(`form_${id}`).submit();
                }
            }
        </script>
    </body>
</html>