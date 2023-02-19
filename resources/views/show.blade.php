<x-app-layout>
<div>
  
</div>
<div class="bg-white py-6 sm:py-8 lg:py-12">
  <div class="max-w-screen-xl px-4 md:px-8 mx-auto">
    <div class="grid md:grid-cols-2 gap-8">
      <!-- images - start -->
      <div class="grid gap-4">
        <div class="inline-flex">
          <a href="{{ route('show_Profile', $post->user->id)}}" class="text-gray-800 hover:text-gray-500 lg:text-lg font-bold transition duration-100">
            <div class="inline-flex">
              <span class="mr-6 inline-flex">
                <img class="w-8 h-8 rounded-full border" alt="*" src="{{ $post->user->image }}">
                <p class="ml-3 pt-1">{{ $post->user->nickname }}</p>
              </span>
              <x-show-button-follow :user="$writer_user" />
            </div>
          </a>
        
          <!-- edit buttons - start -->
          @auth
            @if(!$writer_user->they_isnt_auth_user())
            <div class="flex gap-2.5">
              <a href="/posts/{{ $post->id }}/edit" class="inline-block flex-1 sm:flex-none bg-indigo-500 hover:bg-indigo-600 active:bg-indigo-700 focus-visible:ring ring-indigo-300 text-white text-sm md:text-base font-semibold text-center rounded-full outline-none transition duration-100 px-6 py-2 ">この投稿を編集する</a>
            </div>
            @endif
          @endauth
          <!-- edit buttons - end -->
        </div>
        
        
        <div class="lg:col-span-4 aspect-3/4 h-90 w-auto bg-gray-100 rounded-lg overflow-hidden relative">
          <img src="{{ $post->image }}" loading="lazy" alt="Photo" class="w-full h-full object-cover object-center" />
        </div>
        
        <!-- like - start -->
        <div class="flex items-center gap-3 mb-6 md:mb-10">
          <x-show-button-like :post="$post" />
        </div>
        <!-- like - end -->
        
        <!-- explanation - start -->
        <span class="inline-block text-gray-600 text-lg md:text-base font-semibold mt-3 sm:mt-4">説明</span>
        <div class="bg-gray-100 rounded-lg relative p-5 pt-8">
          <div class="mb-2 md:mb-3">
            <!-- タイトル用　h3 class="text-indigo-500 text-lg md:text-xl font-semibold mb-3">{ $post->title }</h3-->
            <p class="text-gray-600">{{ $post->body }}</p>
          </div>
        </div>
        <!-- explanation - end -->
        
      </div>
      <!-- images - end -->

      <!-- content - start -->
      <div class="md:py-2">

        <!-- items - start -->
        @if(count($items) != 0)
          <div class="flex flex-col gap-4 md:gap-6 mb-6 sm:mb-8">
            <span class="inline-block text-gray-600 text-lg md:text-base font-semibold sm:mt-4">使用アイテム</span>
            <div class="mb-4 md:mb-6">
              @foreach($items as $item)
                <x-show-URL :item="$item" />
              @endforeach
            </div>
          </div>
        @endif
        <!-- items - end -->
        
        <!-- references - start -->
        @if(count($references) != 0)
          <div class="flex flex-col gap-4 md:gap-6 mb-6 sm:mb-8">
            <span class="inline-block text-gray-600 text-lg md:text-base font-semibold mt-3 sm:mt-4">参考にしたサイト</span>
            <div class="mb-4 md:mb-6">
              @foreach($references as $reference)
              <x-show-URL :item="$reference" />
            @endforeach
            </div>
          </div>
        @endif
        <!-- references - end -->
        
        <!-- comments start-->
        <div name="comments">
            <span class="inline-block text-gray-600 text-lg md:text-base font-semibold mt-3 sm:mt-4">コメント</span>
            
            <div id="RTP_Form" class="my-2">
             <form action="{{ route('replyPost', $post) }}" method="POST">
                  @csrf
                  <div class="my-2">
                      <textarea name="comment[body]" rows="4" class="resize-none block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="コメント&#40;200文字まで&#41;"></textarea>
                      <p class="body__error" style="color:red">{{ $errors->first('comment.body') }}</p>
                  </div>
                  <input type="submit" class="bg-blue-600 hover:bg-blue-500 text-white rounded px-4 py-2" value="コメントを送信"/>
              </form>
            </div>
            <!--コメントは存在するか-->
            <div class="py-4">
              @if(count($comments) !== 0)
              <div class=" border rounded-lg py-4">
                @foreach ($comments as $comment)
                <div class="inline-flex px-4 py-2">
                    <span class="mr-6 inline-flex">
                      <img class="w-8 h-8 rounded-full border" alt="*" src="{{ $comment->user->image }}">
                      <p class="ml-3 pt-1">{{ $comment->user->nickname }}</p>
                      <div class="ml-8 mt-1 text-gray-600">
                        {{ $comment->body }}
                        <br>
                      </div>
                    </span>
                </div>

                @endforeach
              </div>
              @else
                <span class="inline-block text-gray-600 text-lg md:text-base font-semibold mt-3 px-4 sm:mt-4">コメントはまだありません</span>
              @endif
            </div>
        </div>
        <!-- comments end -->
      </div>
      <!-- content - end -->
    </div>
  </div>
</div>
</x-app-layout>