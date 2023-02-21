<x-app-layout>
    <div class="bg-white py-6 sm:py-8 lg:py-12">
        <div class="max-w-screen-2xl px-4 md:px-8 mx-auto">
            <h2 class="text-gray-800 text-2xl lg:text-3xl font-bold text-center mb-4 md:mb-8">新規投稿</h2>
            
            <!--エラー文-->
            @if ($errors->any())
                <div class="max-w-2xl mx-auto flex p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                  <svg aria-hidden="true" class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                  <div>
                    <span class="font-medium">投稿できませんでした。以下の点を修正した後、もう一度お試しください</span>
                      <ul class="mt-1.5 ml-4 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                  </div>
                </div>
            @endif
        
            <form action="/posts" method="POST" enctype="multipart/form-data" class="max-w-2xl rounded-lg mx-auto">
                @csrf
                <div class="flex flex-col gap-4 p-4 md:p-8">
                    <div class="pb-8">
                        <span class="h-8 w-8 text-white text-sm bg-gray-400 font-semibold inline-flex text-center p-1.5 rounded-full mb-3">
                            <p class="mx-auto">1</p>
                        </span>
                        <label for="image" class="inline-block px-2 text-gray-700 text-lg font-semibold sm:text-st">服を着ている写真</label>
                        <span class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full">必須</span>
                        
                        <img id="preview" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" class="aspect-3/4 h-60 bg-gray-50 text-gray-600 border border-gray-300 w-auto mb-2 rounded-lg mx-auto object-cover object-center">
                        
                        <input type="file" name="post[image]" accept=".gif, .jpg, .png" onchange="previewImage(this);" class="w-full bg-gray-50 text-gray-600 border border-gray-300 focus:ring ring-indigo-300 rounded outline-none transition duration-100 px-3 py-2">
                        <p class="mt-1 text-sm text-gray-500" id="file_input_help">PNG / JPG / GIF (1MB以内まで)</p>
                    </div>
        
                    <div class="pb-8">
                        <span class="h-8 w-8 text-white text-sm bg-gray-400 font-semibold inline-flex text-center p-1.5 rounded-full mb-3">
                            <p class="mx-auto">2</p>
                        </span>
                        <span class="inline-flex">
                            <label for="items" class="inline-block px-2 text-gray-700 text-lg font-semibold sm:text-st">着用・使用したアイテムのURL</label>
                            <p class="text-sm text-gray-500 pt-1">&#40;最大10個まで&#41;</p>
                        </span>
                        <div class="items">
                            @for ($i=0; $i<10; $i++)
                            <input type="text" name="items[]" value="{{ old('items[$i]') }}" placeholder="https://..." class="mb-3 w-full bg-gray-50 text-gray-700 border border-gray-300 focus:ring-indigo-300 rounded outline-none transition duration-100 px-3 py-2"/><br>
                            @endfor
                        </div>
                    </div>
                    
                    <div class="pb-8">
                        <span class="h-8 w-8 text-white text-sm bg-gray-400 font-semibold inline-flex text-center p-1.5 rounded-full mb-3">
                            <p class="mx-auto">3</p>
                        </span>
                        <span class="inline-flex">
                            <label for="items" class="inline-block px-2 text-gray-700 text-lg font-semibold sm:text-st">参考サイトのURL</label>
                            <p class="text-sm text-gray-500 pt-1">&#40;最大5個まで&#41;</p>
                        </span>
                        <p class="text-sm text-gray-500 pb-2 pl-11">参考にしたサイトのURLもあると、他の人も参考にしやすい投稿になります！</p>
                        <div class="references">
                            @for ($i=0; $i<5; $i++)
                            <input type="text" name="references[]" value="{{ old('references[$i]') }}" placeholder="https://..." class="mb-3 w-full bg-gray-50 text-gray-700 border border-gray-300 focus:ring-indigo-300 rounded outline-none transition duration-100 px-3 py-2"/><br>
                            @endfor
                        </div>
                    </div>
                    
                    <div class="pb-8">
                        <span class="h-8 w-8 text-white text-sm bg-gray-400 font-semibold inline-flex text-center p-1.5 rounded-full mb-3">
                            <p class="mx-auto">4</p>
                        </span>
                        <span class="inline-flex">
                            <label for="caps" class="inline-block px-2 text-gray-700 text-lg font-semibold sm:text-st">説明</label>
                            <p class="text-sm text-gray-500 pt-1">&#40;最大500字まで&#41;</p>
                        </span>
                        <textarea id="body" name="post[body]" rows="4" placeholder="制作時のこだわりや、着る際のアドバイスなどがあれば書いてみましょう" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-300 focus:border-indigo-300">{{ old('post.body')}}</textarea>
                    </div>
            
                    <input type="submit" value="投稿する" class="block bg-blue-600 hover:bg-blue-500 active:bg-blue-400 focus-visible:ring ring-blue-300 text-white text-sm md:text-base font-semibold text-center rounded-lg outline-none transition duration-100 px-8 py-3"/>
                </div>
            </form>
            
            <script>
            function previewImage(obj)
            {
            	var fileReader = new FileReader();
            	fileReader.onload = (function() {
            		document.getElementById('preview').src = fileReader.result;
            	});
            	fileReader.readAsDataURL(obj.files[0]);
            }
            </script>
            
        </div>
    </div>
</x-app-layout>