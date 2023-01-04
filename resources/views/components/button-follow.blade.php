<span>
    <!-- 見ているユーザーが本人では"ない"ことを確認 -->
    @if($user->they_isnt_auth_user())
        <!-- ユーザーが「フォロー」をしていたら -->
        @if($user->is_followed_by_auth_user())
                
            <!-- 「フォロー」取消用ボタンを表示 -->
           <a href="{{ route('unfollow', $user) }}" class="bg-blue-600 hover:bg-blue-500 text-white rounded px-4 py-2">フォロー済</a>
                
        <!-- まだユーザーが「フォロー」をしていなければ、ボタンを表示 -->
        @else
            <a href="{{ route('follow', $user) }}" class="border-2 border-gray-700 bg-white hover:border-gray-500 rounded px-4 py-1.5">フォロー</a>
        @endif
    @endif
</span>