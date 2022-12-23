<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Kitemite</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <!--ボタンギミック用スタイル-->
        <style>
        
        </style>
    </head>
    
    <body>
        <h1>Kitemite</h1>
        <h3 class="username">
            <a href="/users/{{ $user->id }}">{{ $user->nickname }}</a>
        </h3>
        
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
            
        <!--likeボタン-->
            <span>
                <!-- ユーザーが「いいね」をしていたら -->
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
        <!-- 見ているユーザーが本人か確認 -->
                @if(!($user->they_isnt_auth_user()))
                    <div class="edit">
                        <a href="/posts/{{ $post->id }}/edit">編集する</a>
                    </div>
                @endif
        
        <div class="content">
            <div class="content_post">
                
                <!--画像のサイズ調整すること-->
                <div class='image'>
                    <img src="{{ $post->image }}">
                </div>
                
                <!--Itemを表示する-->
                <div class="items">
                    <h2>使用アイテム</h2>
                    
                    @foreach ($items as $item)
                        <p>{{ $item->URL }}</p>
                        <a href="/search/items/{{ $item->id }}">このアイテムを使ったコーデを見る</a>
                    @endforeach
                </div>
                
                
                <p class='body'>{{ $post->body }}</p>
                <div class='tags'>
                    <p style="display:inline">タグ</p>
                    <p style="display:inline">{{ $post->tag1 }}</p>
                    <p style="display:inline">　{{ $post->tag2 }}</p>
                    <p style="display:inline">　{{ $post->tag3 }}</p>
                    <p style="display:inline">　{{ $post->tag4 }}</p>
                    <p style="display:inline">　{{ $post->tag5 }}</p>
                </div>
            </div>
        </div>
        
        <form action="/posts/{{ $post->id }}" id="form_{{ $post->id }}" method="post">
            @csrf
            @method('DELETE')
            <button type="button" onclick="deletePost({{ $post->id }})">この投稿を削除する</button> 
        </form>
        
        <!--投稿に対する返信フォーム-->
        <div class= "ReplyToPost">
            <!--button id="btn">この投稿に返信する</button-->
            
            <!--以下は押されたら出る"hidden属性"-->
            <div id="RTP_Form" class="hidden">
               <form action="{{ route('replyPost', $post) }}" method="POST">
                    @csrf
                    <div>
                        <textarea name="comment[body]" placeholder="200文字まで"></textarea>
                        <p class="body__error" style="color:red">{{ $errors->first('comment.body') }}</p>
                    </div>
                    <input type="submit" value="送信"/>
                </form>
            </div>
            
        </div>
        
        
        <!--コメント表示-->
        <div class="comments">
            <h2>コメント</h2>
            <!--コメントは存在するか-->
            @if($comments)
            
                @foreach ($comments as $comment)
                    <p>{{ $comment->user->nickname }}</p>
                    
                    <!--リプライとなっているか-->
                    @if($comment->reply_user_id)
                        To＠{{ $comment->reply_user->addressname }}
                    @endif
                    
                    {{ $comment->body }}
                    <br>
                    
                    <!--投稿に対する返信フォーム-->
                    <div class= "ReplyToComment">
                    <!--button id="btn">この投稿に返信する</button-->

                        <!--以下は押されたら出る"hidden属性"-->
                        <div id="RTC_Form" class="hidden">
                           <form action="{{ route('replyComment', ['post' => $post, 'comment' => $comment]) }}" method="POST">
                            @csrf
                                <div>
                                    <textarea name="comment[body]" placeholder="200文字まで"></textarea>
                                    <p class="body__error" style="color:red">{{ $errors->first('comment.body') }}</p>
                                </div>
                                <input type="submit" value="送信"/>
                            </form>
                        </div>
    
                        <p>このコメントへの返信</p>
    
                    </div>
                @endforeach
                
            @else
                <p>まだコメントはありません</p>
                
            @endif
        </div>
        
        <div class="footer">
            <a href="/">戻る</a>
        </div>
        
        <!--削除用スクリプト-->
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