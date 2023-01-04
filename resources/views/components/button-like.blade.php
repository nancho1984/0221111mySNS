<!--いいねボタン-->
<span class="text-gray-600 lg:text-lg font-bold">
    <!--ユーザーはいいねをしているか？-->
    @if($post->is_liked_by_auth_user())
        <!--して「いる」とき-->
        <a href="{{ route('unlike', $post) }}" class="btn btn-success btn-sm">
            <img class="w-10 h-10" alt="いいね済" src="https://res.cloudinary.com/ds5zchxhn/image/upload/v1672754593/fqyc6hpjin9znknxaid8.png">
        </a>
    @else
        <!--して「ない」とき-->
        <a href="{{ route('like', $post) }}" class="btn btn-success btn-sm">
            <img class="w-10 h-10" alt="いいね" src="https://res.cloudinary.com/ds5zchxhn/image/upload/v1672754554/vu1ysfcu99prcmpfqae3.png">
        </a>
    @endif
</span>
<!--いいね数のカウント-->
<span class="text-gray-600 text-sm pr-3.9">{{ $post->likes->count() }}</span>