<x-app-layout>
<!--str_replace'検索文字列,'変換後','検索対象'/ app getLocaleはユーザーの言語環境-->
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <body class="bg-white">
        <div class="bg-white pl-8 py-6 sm:py-8 lg:py-12 drop-shadow-md">
            <x-profile-header :user="$user"/>
        </div>
        
        <div class="bg-white px-8 py-6 sm:py-8 lg:py-12">
            <div class="pt-2 pb-6">
                <h2 class="text-2xl font-medium">このユーザーの投稿</h2>
                <x-top-post :posts="$users_posts" />
                
                @if(count($users_posts) >= 3)
                    <p class="float-right pr-4"><a href="{{ route('users_posts', $user->id) }}" class="btn btn-success btn-sm">もっと見る -></a></p>
                @endif
                
                @if(count($users_posts) === 0)
                    <p>このユーザーの投稿はまだありません</p>
                @endif
            </div>
        
            <div class="pt-2 pb-6">
                <h2 class="text-2xl font-medium">このユーザーがいいねした投稿</h2>
                <x-top-post :posts="$liked_posts" />
                @if(count($liked_posts) >= 3)
                    <p class="float-right pr-4"><a href="{{ route('postsliked', $user->id) }}" class="btn btn-success btn-sm">もっと見る -></a></p>
                @endif
                
                @if(count($liked_posts) === 0)
                    <p class="py-4 mx-auto">いいねした投稿はまだありません</p>
                @endif
            </div>
    
            <div class="footer">
                <a href="/" class="border-2 border-gray-700 bg-white hover:border-gray-500 rounded px-4 py-1.5">戻る</a>
            </div>
        </div>
    </body>
</html>
</x-app-layout>