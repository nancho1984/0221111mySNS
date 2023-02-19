<!--いいねボタン-->
<!--ユーザーはいいねをしているか？-->
@if($post->is_liked_by_auth_user())
    <!--して「いる」とき-->
    <a href="{{ route('unlike', $post) }}" class="btn btn-success btn-sm">
        <div class="h-8 flex items-center bg-pink-500 text-white rounded-full gap-1 px-2 pr-6">
            <img class="w-10 h-10 invert brightness-20" alt="いいね済" src="https://res.cloudinary.com/ds5zchxhn/image/upload/v1672754593/fqyc6hpjin9znknxaid8.png">
            <span class="text-sm">いいね {{ $post->likes->count() }}</span>
        </div>
    </a>
@else
    <!--して「ない」とき-->
    <a href="{{ route('like', $post) }}" class="btn btn-success btn-sm">
        <div class="h-8 flex items-center border border-gray-500 bg-white rounded-full gap-1 px-2 pr-6">
            <img class="w-10 h-10" alt="いいね" src="https://res.cloudinary.com/ds5zchxhn/image/upload/v1672754554/vu1ysfcu99prcmpfqae3.png">
            <span class="text-sm">いいね {{ $post->likes->count() }}</span>
        </div>
    </a>
@endif