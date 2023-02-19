<div class="flex border rounded-lg overflow-hidden gap-x-4 sm:gap-y-4 lg:gap-6 inline-flex relative">
    <a href="{{ $item->URL }}" class="group w-36 h-36 block overflow-hidden">
      <img src="{{ $item->og_image }}" loading="lazy" alt="Photo" class="w-36 h-36 object-cover object-center group-hover:scale-110 transition duration-200" />
    </a>

    <div class="flex flex-col justify-between flex-1 py-4">
      <div class="pr-4">
        <a href="{{ $item->URL }}" class="inline-block text-gray-800 hover:text-gray-500 text-sm font-bold transition duration-100 mb-1">
          {{ $item->og_title }}
        </a>
        <span class="block text-gray-500">{{ $item->URL }}</span>
      </div>
    </div>
    
 </div>