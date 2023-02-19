<!DOCTYPE html>
<x-app-layout>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <body class="max-w-screen-2xl px-4 md:px-8 mx-auto">
        <h2>新規投稿</h2>
        <!--エラー文-->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                </ul>
            </div>
        @endif
        
        <form action="/posts" method="POST" enctype="multipart/form-data">
            @csrf
            <p>画像</p>
            <div class="image">
                
                <input 
                    type="file"
                    name="post[image]"
                    accept=".gif, .jpg, .png">
            </div>
            
            <div class="items">
                <!-- アイテムを追加ボタンを押したら、次のフォームが出るようにすること-->
                <p>使用アイテム</p>
                @for ($i=0; $i<10; $i++)
                <input type="text" name="items[]" placeholder="使用アイテムのURL" value="{{ old('items[$i]') }}" /><br>
                @endfor
            </div>
            <div class="references">
                <!-- アイテムを追加ボタンを押したら、次のフォームが出るようにすること-->
                <p>参考サイト</p>
                <p>使用したアイテムだけでなく、参考にしたサイトのURLもあると、他の人も参考にしやすい投稿になります！</p>
                @for ($i=0; $i<5; $i++)
                <input type="text" name="references[]" placeholder="参考サイトのURL" value="{{ old('items[$i]') }}" /><br>
                @endfor
            </div>
            
            <div class="body">
                <p>説明</p>
                <textarea name="post[body]" placeholder="&#40;500字まで&#41;">{{ old('post.body')}}</textarea>
            </div>
            <div class="tags">
                <p>タグ</p>
                <!-- タグを追加ボタンを押したら、次のフォームが出るようにすること-->
                <input type="text" name="tags[0]" placeholder="タグ&#40;20字まで&#41;" value="{{ old('tags.0') }}"/>
                <input type="text" name="tags[1]" placeholder="タグ&#40;20字まで&#41;" value="{{ old('tags.1') }}"/>
                <input type="text" name="tags[2]" placeholder="タグ&#40;20字まで&#41;" value="{{ old('tags.2') }}"/>
                <input type="text" name="tags[3]" placeholder="タグ&#40;20字まで&#41;" value="{{ old('tags.3') }}"/>
                <input type="text" name="tags[4]" placeholder="タグ&#40;20字まで&#41;" value="{{ old('tags.4') }}"/>
            </div>
            <!--下書き保存作れたらいいな…-->
            <input type="submit" value="投稿する"/>
        </form>
        <div class="footer">
            <a href="/">戻る</a>
        </div>
    </body>
</html>
</x-app-layout>
