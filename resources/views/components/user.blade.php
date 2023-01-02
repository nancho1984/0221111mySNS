<!--[必要な変数]
    $user : 表示するためのuserインスタンス-->

<div name="container" class="">
    <a href={{ route('show_post', $user_array['users_post']->id) }} class="group h-96 block bg-gray-100 rounded-t-lg overflow-hidden relative">
        <!--loading="lazy"にすると、画像の遅延読み込みができるらしい-->
        <img src="{{ $user_array['users_post']->image }}" loading="lazy" class="w-full h-full object-cover object-center group-hover:opacity-70 transition duration-200" />
    </a>
    <div class="flex justify-between items-start bg-gray-100 rounded-b-lg gap-2 p-4">
        <div class="flex flex-col">
            <a href="{{ route('show_Profile', $user_array['user']->id)}}" class="text-gray-800 hover:text-gray-500 lg:text-lg font-bold transition duration-100">
                <img class="w-8 h-8 rounded-full" alt="*" src="{{ $user_array['user']->image }}">
                {{ $user_array['user']->nickname }}
            </a>
        </div>
        <div class="flex flex-col items-end">
            <!--followボタン-->
            <span class="text-gray-600 lg:text-lg font-bold">
                <!-- 見ているユーザーが投稿者「ではない」ことを確認 -->
                @if($user_array['user']->they_isnt_auth_user())
                    <!--本人「ではない」とき-->
                    <!-- 見ているユーザーが、投稿をしたユーザーを「フォロー」していたら -->
                    @if($user_array['user']->is_followed_by_auth_user())
                        <!-- 「フォロー」取消用ボタンを表示 -->
                        <a href="{{ route('unfollow', $user_array['user']) }}" class="btn btn-success btn-sm">
                        フォロー解除
	                       </a>

                        <!-- まだユーザーが「フォロー」をしていなければ、ボタンを表示 -->
                    @else
        	            <a href="{{ route('follow', $user_array['user']) }}" class="btn btn-secondary btn-sm">
    	    	            フォロー
                    	</a>
                    @endif
                @endif
            </span>
        </div>
    </div>
</div>