<div class="flex flex-wrap grid grid-cols-3 gap-x-2 gap-y-2">
    @foreach($posts as $post)
        <x-post :post="$post" />
    @endforeach
</div>
    
<div name='paginate'>
    {{ $posts->links() }}
</div>
