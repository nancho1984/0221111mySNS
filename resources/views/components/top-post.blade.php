<div name="top_posts" class="flex flex-wrap grid grid-cols-3">
    @foreach($posts as $post)
        <x-post :post="$post" />
    @endforeach
</div>
