<x-app-layout>
    <!--str_replace'検索文字列,'変換後','検索対象'/ app getLocaleはユーザーの言語環境-->
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
        <body>
            
            <!--a href="/test">test</a-->
            
            <div class="bg-white py-6 sm:py-8 lg:py-12">
                <div class="max-w-screen-2xl px-8 md:px-8 mx-auto">
                        
                        <!--検索フォーム　あとでナビバーに-->
                    
                        
                    <div class="py-4">
                        @auth
                            <a href="/posts/create" class="bg-blue-600 hover:bg-blue-500 text-white rounded px-4 py-2">新規投稿</a>
                            <a href="/users/ {{$auth_user->id}}" class="border-2 border-gray-700 bg-white hover:border-gray-500 rounded px-4 py-1.5">マイページ</a>
                        @else
                            <!--ログインして「ない」とき-->
                            <a href="/register" class="bg-blue-600 hover:bg-blue-500 text-white rounded px-4 py-2">新規登録</a>
                            <a href="/login" class="border-2 border-gray-700 bg-white hover:border-gray-500 rounded px-4 py-1.5">ログイン</a>
                        @endauth    
                    </div>
                        
                        @auth
                            @if($follows_new_posts->count() !== 0)
                                <div class="pt-2 pb-6">
                                    <h2 class="text-2xl font-medium">フォロー新着</h2>
                                    <x-top-post :posts="$follows_new_posts" />
                                    <p class="float-right pr-4"><a href="{{ route('show_follows_posts') }}" class="btn btn-success btn-sm">もっと見る -></a></p>
                                </div>
                            @endif
                        @endauth
                        
                        <div class="pt-2 pb-6">
                            <h2 class="text-2xl font-medium">人気の投稿</h2>
                            <x-top-post :posts="$popular_posts" />
                            <p class="float-right pr-4"><a href="{{ route('show_month_popular_posts') }}" class="btn btn-success btn-sm">もっと見る -></a></p>
                        </div>
                        
                        <div class="pt-2 pb-6">
                            <h2 class="text-2xl font-medium">みんなの新着</h2>
                            <x-top-post :posts="$new_posts" />
                            <p class="float-right pr-4"><a href="{{ route('show_new_posts') }}" class="btn btn-success btn-sm">もっと見る -></a></p>
                        </div>
                        
                </div>
            </div>
            
        </body>
    </html>
</x-app-layout>