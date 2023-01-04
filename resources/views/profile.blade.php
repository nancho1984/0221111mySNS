<x-app-layout>
<!--str_replace'検索文字列,'変換後','検索対象'/ app getLocaleはユーザーの言語環境-->
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <body class="bg-white">
        <div class="bg-white pl-8 py-6 sm:py-8 lg:py-12">
            <x-profile-header :user="$user"/>
        
            <div class="pt-2 pb-6">
                <h2 class="text-2xl font-medium">このユーザーの投稿</h2>
                <x-top-post :posts="$users_posts" />
                <p class="float-right pr-4"><a href="{{ route('users_posts', $user->id) }}" class="btn btn-success btn-sm">もっと見る -></a></p>
            </div>
        
            <div class="pt-2 pb-6">
                <h2 class="text-2xl font-medium">いいねした投稿</h2>
                <x-top-post :posts="$liked_posts" />
                <p class="float-right pr-4"><a href="{{ route('postsliked', $user->id) }}" class="btn btn-success btn-sm">もっと見る -></a></p>
            </div>
    
            <div class="footer">
                <a href="/">戻る</a>
            </div>
        </div>
    </body>
</html>
</x-app-layout>