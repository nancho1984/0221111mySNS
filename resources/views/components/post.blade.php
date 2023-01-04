<div name="container" class="p-2">
    <a href={{ route('show_post', $post->id) }} class="group aspect-3/4 h-auto block bg-gray-100 rounded-t-lg overflow-hidden relative">
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
        <div class="flex flex-col items-end inline-flex">
            <x-button-like :post="$post" />
        </div>
    </div>
</div>