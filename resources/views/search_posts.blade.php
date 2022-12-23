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
        <form method="GET" action="{{ route('search_posts') }}">
            <input type="search" placeholder="コーデで検索" name="search_posts" value="@if (isset($search)) {{ $search }} @endif">
            <div>
                <button type="submit">検索</button>
                <button>
                    <a href="{{ route('search_posts') }}" class="text-white">
                        クリア
                    </a>
                </button>
            </div>
        </form>
        
        <div class='posts'>
            <h2>{{ $searchPhrase }}</h2>
            @foreach ($posts as $post)
            <div class='post'>
                <a href="/users/{{ $post->user->id }}">{{ $post->user->nickname }}</a>
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