<div name="container" class="">
    <a href={{ route('show_post', $post->id) }} class="group h-96 block bg-gray-100 rounded-t-lg overflow-hidden relative">
        <!--loading="lazy"にすると、画像の遅延読み込みができるらしい-->
        <img src="{{ $post->image }}" loading="lazy" class="w-full h-full object-cover object-center group-hover:opacity-70 transition duration-200" />
    </a>
    <div class="flex justify-between items-start bg-gray-100 rounded-b-lg gap-2 p-4">
        <div class="flex flex-col">
            <a href="{{ route('show_Profile', $post->user->id)}}" class="text-gray-800 hover:text-gray-500 lg:text-lg font-bold transition duration-100">
                <img class="w-8 h-8 rounded-full" alt="*" src="{{ $post->user->image }}">
                {{ $post->user->nickname }}
            </a>
        </div>
        <div class="flex flex-col items-end">
            <!--いいねボタン-->
            <span class="text-gray-600 lg:text-lg font-bold">
                <!--ユーザーはいいねをしているか？-->
                @if($post->is_liked_by_auth_user())
                    <!--して「いる」とき-->
                    <a href="{{ route('unlike', $post) }}" class="btn btn-success btn-sm">
                        いいね済
                    </a>
                @else
                    <!--して「ない」とき-->
                    <a href="{{ route('like', $post) }}" class="btn btn-success btn-sm">
                        いいね
                    </a>
                @endif
            </span>
            <!--いいね数のカウント-->
            <span class="text-gray-600 text-sm">{{ $post->likes->count() }}</span>
        </div>
    </div>
</div>