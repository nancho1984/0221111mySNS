<x-app-layout>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
        <body>
            <div class="bg-white py-6 sm:py-8 lg:py-12">
                <div class="max-w-screen-2xl px-8 md:px-8 mx-auto">
                    <x-normal-header pageTitle="直近1か月で人気の投稿"/>
                    <x-show-posts-nopaginate :posts="$month_popular_posts" />
                </div>
            </div>
        </body>
    </html>
</x-app-layout>