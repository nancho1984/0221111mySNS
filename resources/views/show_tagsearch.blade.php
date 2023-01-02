<x-app-layout>
    <!DOCTYPE html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
        <body>
            <x-normal-header pageTitle="#{{ $search_tag }}"/>
            <div class="bg-white py-6 sm:py-8 lg:py-12">
                <div class="max-w-screen-2xl px-8 md:px-8 mx-auto">
                    <x-show-posts :posts="$new_posts" />
                </div>
            </div>
        </body>
    </html>
</x-app-layout>