<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>Kitemite</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>
    <body>
        <h1>kitemite</h1>
        <h2>投稿の編集</h2>
        
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
        
        <form action="/posts/{{ $post->id }}/update" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <img src="{{ $post->image }}" width="" height="">
            <p>別の画像と入れ替える</p>
            <div class="image">
                <input
                    type="file" 
                    name="postupdate[image]" 
                    accept=".gif,.jpg,.png">
                    
            </div>
            
            <div class="items">
                <p>使用/参考にしたアイテム</p>
                
                @for ($i=0; $i<$count_items; $i++)
                <input type="text" name="items[URL][]" value="{{ $items[$i]->URL }}" /><br>
                @endfor
                
                @for ($i=$count_items; $i<10; $i++)
                <input type="text" name="items[URL][]" value="{{ old('items[URL][$i]') }}" /><br>
                @endfor
            </div>
            
            <div class="body">
                <p>説明</p>
                <textarea name="postupdate[body]" placeholder="&#40;500字まで&#41;">{{ $post->body }}</textarea>
            </div>
            
            <div class="tags">
                <p>タグ</p>
                <!-- タグを追加ボタンを押したら、次のフォームが出るようにすること-->
                <input type="text" name="postupdate[tag1]" placeholder="タグ1&#40;20字まで&#41;" value="{{ $post->tag1 }}"/>
                <input type="text" name="postupdate[tag2]" placeholder="タグ2&#40;20字まで&#41;" value="{{ $post->tag2 }}"/>
                <input type="text" name="postupdate[tag3]" placeholder="タグ3&#40;20字まで&#41;" value="{{ $post->tag3 }}"/>
                <input type="text" name="postupdate[tag4]" placeholder="タグ4&#40;20字まで&#41;" value="{{ $post->tag4 }}"/>
                <input type="text" name="postupdate[tag5]" placeholder="タグ5&#40;20字まで&#41;" value="{{ $post->tag5 }}"/>
            </div>
            <!--下書き保存作れたらいいな…-->
            <input type="submit" value="投稿する"/>
        </form>
        <div class="footer">
            <a href="/">戻る</a>
        </div>
    </body>
</html>