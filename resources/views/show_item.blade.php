<x-app-layout>
    <!DOCTYPE html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
        <body>
            <x-show-posts pageTitle="同じアイテムを使用した投稿" :posts="$posts" />
        </body>
    </html>
</x-app-layout>