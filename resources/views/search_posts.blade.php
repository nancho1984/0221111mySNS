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
        
        <div class='posts'>
            <h2>「{{ $searchPhrase }}」のコーデの検索結果</h2>
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
            
            <!--下のようにページネーターを書くと
            ページネートで次ページに遷移しても、検索結果を保持するらしい-->
            @if(count($posts)>0)
                {{ $posts->appends(request()->input())->links() }}
            @else
                <p>{{ $searchPhrase}}の検索結果はありません</p>
            @endif
        </div>
    </body>
</html>