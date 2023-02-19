<!--フォローボタン-->
<!-- 見ているユーザーが本人では"ない"ことを確認 -->
@if($user->they_isnt_auth_user())
    <!-- ユーザーが「フォロー」をしていたら -->
    @if($user->is_followed_by_auth_user())
        <a href="{{ route('unfollow', $user) }}" class="btn btn-success btn-sm">
            <div class="h-8 flex items-center bg-blue-600 hover:bg-blue-500 text-white rounded-full gap-1 px-4">
                <span class="text-sm">フォロー中</span>
            </div>
        </a>
    @else
        <!-- 「フォロー」してなかったら -->
        <a href="{{ route('follow', $user) }}" class="btn btn-success btn-sm">
            <div class="h-8 flex items-center border border-gray-500 bg-white rounded-full gap-1 px-4">
                <span class="text-sm">フォロー</span>
            </div>
        </a>
    @endif
@endif