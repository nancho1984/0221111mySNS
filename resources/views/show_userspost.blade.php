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
        
        <p>＠{{$user->name}}の投稿</p>
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