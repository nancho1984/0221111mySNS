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
        <h2>＠{{$user->name}}のプロフィール</h2>
        
        <div class='image'>
            <img src="{{ $user->image }}">
        </div>
        
        <span>
            <h2>＠{{ $user->name }}</h2>
        
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
        
        <h2>投稿</h2>
        
        <a href="{{ route('users_posts', $user->id) }}">他の投稿も見る</a>
        
        <h2>いいねした投稿</h2>
        
        <a href="{{ route('postsliked', $user->id) }}">他の投稿も見る</a>
        
        
        <div class="footer">
            <a href="/">戻る</a>
        </div>
    </body>
</html>