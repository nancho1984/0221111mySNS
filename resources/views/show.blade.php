<x-app-layout>
    <!DOCTYPE html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
        <body>
            <h3 class="username">
                <a href="/users/{{ $writer_user->id }}">{{ $writer_user->nickname }}</a>
            </h3>
            
            @auth
                <!--followボタン-->
                <span>
                    <!-- 見ているユーザーが投稿者「ではない」ことを確認 -->
                    @if($writer_user->they_isnt_auth_user())
                        <!--本人「ではない」とき-->
                        <!-- 見ているユーザーが、投稿をしたユーザーを「フォロー」していたら -->
                        @if($writer_user->is_followed_by_auth_user())
                            <!-- 「フォロー」取消用ボタンを表示 -->
                            <a href="{{ route('unfollow', $writer_user) }}" class="btn btn-success btn-sm">
                            フォロー解除
	                        </a>

                            <!-- まだユーザーが「フォロー」をしていなければ、ボタンを表示 -->
                        @else
            	            <a href="{{ route('follow', $writer_user) }}" class="btn btn-secondary btn-sm">
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
                
                <!-- 見ているユーザーが投稿者本人で「ある」か確認 -->
                @if(!($writer_user->they_isnt_auth_user()))
                    <div class="edit">
                        <a href="/posts/{{ $post->id }}/edit">編集する</a>
                    </div>

                    <form action="/posts/{{ $post->id }}" id="form_{{ $post->id }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="deletePost({{ $post->id }})">この投稿を削除する</button> 
                    </form>
                @endif
            @endauth
        
            <div class="content">
                <div class="content_post">
                
                    <!--画像のサイズ調整すること-->
                    <div class='image'>
                        <img src="{{ $post->image }}">
                    </div>
                    
                    <div>
                        <!--Itemを表示する-->
                        <div class="items">
                            <h2>使用アイテム</h2>
                            @foreach ($items as $item)
                                <a href="{{ $item->URL }}">
                                    <img src="{{ $item->og_image }}">
                                    @if($item->og_title !== false)
                                        <p>{{$item->og_title}}</p>
                                    @else
                                        {{ $item->URL }}
                                    @endif
                                </a>
                                <p><a href="/search/items/{{ $item->id }}">このアイテムを使ったほかのコーデを見る</a></p>
                            @endforeach
                        </div>
                        
                        <div class="references">
                            <h2>参考サイト</h2>
                            @foreach ($references as $reference)
                                <a href="{{ $reference->URL }}">
                                    <img src="{{ $reference->og_image }}">
                                    @if($reference->og_title !== false)
                                        <p>{{$reference->og_title}}</p>
                                    @else
                                        {{ $reference->URL }}
                                    @endif
                                </a>
                                <p><a href="/search/items/{{ $reference->id }}">このアイテムを使ったほかのコーデを見る</a></p>
                            @endforeach
                        </div>
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
</x-app-layout>