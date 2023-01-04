<div class="flex flex-wrap border rounded-lg overflow-hidden gap-x-4 sm:gap-y-4 lg:gap-6">
    <a href="#" class="group w-32 sm:w-40 h-48 sm:h-56 block bg-gray-100 overflow-hidden relative">
        <img src="{{ $item->og_image }}" loading="lazy" class="w-30 h-30 object-cover object-center group-hover:scale-110 transition duration-200" />
        
        <div class="flex flex-col justify-between flex-1 py-4">
        <div>
            <p class="inline-block text-gray-800 hover:text-gray-500 text-lg lg:text-xl font-bold transition duration-100 mb-1">{{ $item->og_title }}</p>
            <span class="block text-gray-500">{{ $item->URL }}</span>
            <a href="/search/items/{{ $item->id }}" class="block text-gray-500">このアイテムを使ったほかのコーデを見る</a>
        </div>
    </div>
    </a>
</div>