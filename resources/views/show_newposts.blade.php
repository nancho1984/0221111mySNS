<x-app-layout>
    <!DOCTYPE html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
        <body>
            <x-show-posts pageTitle="新着" :posts="$new_posts" />
        </body>
    </html>
</x-app-layout>