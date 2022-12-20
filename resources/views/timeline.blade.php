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
        @if($user)
            <a href="/posts/create">新規投稿</a>
            <a href="/users/ {{$user->id}}">マイページ</a>
        @else
            <a href="/register">新規登録</a>
            <a href="/login">ログイン</a>
        @endif
        
        <div class='posts'>
            <h2>新着</h2>
            @foreach ($posts as $post)
            <div class='post'>
                <a href="/users/{{ $post->user->id }}">＠{{ $post->user->name }}</a>
                <!-- 画像のサイズ調整すること-->
                <div class='image'>
                    <img src="{{ $post->image }}">
                </div>
            </div>
            
            <!--likeボタン-->
            <span>
                <!-- もし$likeがあれば＝ユーザーが「いいね」をしていたら -->
                @if($post->is_liked_by_auth_user())
                
                    <!-- 「いいね」取消用ボタンを表示 -->
    	            <a href="{{ route('unlike', $post) }}" class="btn btn-success btn-sm">
	    	            いいね
    		            <!-- 「いいね」の数を表示 -->
	                    <span class="badge">
	    		        {{ $post->likes->count() }}
	    	            </span>
	                </a>
                
                <!-- まだユーザーが「いいね」をしていなければ、「いいね」ボタンを表示 -->
                @else
    	            <a href="{{ route('like', $post) }}" class="btn btn-secondary btn-sm">
    		            いいね
    		            <!-- 「いいね」の数を表示 -->
                		<span class="badge">
    	    	    	{{ $post->likes->count() }}
    	              	</span>
                	</a>
                @endif
            </span>
            <a href="/posts/{{ $post->id }}">詳しく見る</a>
            @endforeach
        </div>
        
        <div class='paginate'>
            {{ $posts->links() }}
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