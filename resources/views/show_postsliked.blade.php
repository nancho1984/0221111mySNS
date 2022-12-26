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
        <h2>{{$user->nickname}}のプロフィール</h2>
        
        <div class='image'>
            <img src="{{ $user->image }}">
        </div>
        
        <span>
            <h2>＠{{ $user->addressname }}</h2>
        
            <!--followボタン-->
            <span>
                <!-- 見ているユーザーが本人か確認 -->
                @if($user->they_isnt_auth_user())
                    <!-- ユーザーが「フォロー」をしていたら -->
                    @if($user->is_followed_by_auth_user())
                
                        <!-- 「フォロー」取消用ボタンを表示 -->
        	            <a href="{{ route('unfollow', $user) }}" class="btn btn-success btn-sm">
	     	            フォロー解除
	                    </a>
                
                    <!-- まだユーザーが「フォロー」をしていなければ、ボタンを表示 -->
                    @else
        	            <a href="{{ route('follow', $user) }}" class="btn btn-secondary btn-sm">
    	    	            フォロー
                    	</a>
                    @endif
                @endif
            </span>
        </span>

        <span>
            <p>フォロー<a href="{{ route('show_followusers', $user) }}">{{ $followusers_count }}</a></p>
            <p>フォロワー<a href="{{ route('show_followers', $user) }}">{{ $followers_count }}</p>
        </span>
        
        <p>{{ $user->profile_sentence }}</p>
        
        <!--過去いいね投稿表示-->
        <div class='posts'>
            <h2>いいねした記事</h2>
            @foreach ($liked_posts as $liked_post)
            <div class='liked_post'>
                <a href="/users/{{ $liked_post->user->id }}">{{ $liked_post->user->nickname }}</a>
                <!-- 画像のサイズ調整すること-->
                <div class='image'>
                    <img src="{{ $liked_post->image }}">
                </div>
            </div>
            
            <!--likeボタン-->
            <span>
                <!-- もし$likeがあれば＝ユーザーが「いいね」をしていたら -->
                @if($liked_post->is_liked_by_auth_user())
                
                    <!-- 「いいね」取消用ボタンを表示 -->
    	            <a href="{{ route('unlike', $liked_post) }}" class="btn btn-success btn-sm">
	    	            いいね
    		            <!-- 「いいね」の数を表示 -->
	                    <span class="badge">
	    		        {{ $liked_post->likes->count() }}
	    	            </span>
	                </a>
                
                <!-- まだユーザーが「いいね」をしていなければ、「いいね」ボタンを表示 -->
                @else
    	            <a href="{{ route('like', $liked_post) }}" class="btn btn-secondary btn-sm">
    		            いいね
    		            <!-- 「いいね」の数を表示 -->
                		<span class="badge">
    	    	    	{{ $liked_post->likes->count() }}
    	              	</span>
                	</a>
                @endif
            </span>
            <a href="/posts/{{ $liked_post->id }}">詳しく見る</a>
            @endforeach
        </div>
        
        <div class='paginate'>
            {{ $liked_posts->links() }}
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