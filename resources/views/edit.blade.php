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
            
            <div name="URLs">
                <div name="items">
                    <p>使用アイテム</p>
                
                    @for ($i=0; $i<$count_items; $i++)
                    <input type="text" name="items[]" value="{{ $items[$i]->URL }}" /><br>
                    @endfor
                
                    @for ($i=$count_items; $i<10; $i++)
                    <input type="text" name="items[]" value="{{ old('items[$i]') }}" /><br>
                    @endfor
                </div>
                
                <div name="references">
                    <p>参考サイト</p>
                
                    @for ($i=0; $i<$count_references; $i++)
                    <input type="text" name="references[]" value="{{ $references[$i]->URL }}" /><br>
                    @endfor
                
                    @for ($i=$count_references; $i<10; $i++)
                    <input type="text" name="references[]" value="{{ old('references[$i]') }}" /><br>
                    @endfor
                </div>
            </div>
            
            <div class="body">
                <p>説明</p>
                <textarea name="postupdate[body]" placeholder="&#40;500字まで&#41;">{{ $post->body }}</textarea>
            </div>
            
            <div class="tags">
                <p>タグ</p>
                <!-- タグを追加ボタンを押したら、次のフォームが出るようにすること-->
                <input type="text" name="tags[0]" placeholder="タグ&#40;20字まで&#41;" value="{{ $post->tag1 }}"/>
                <input type="text" name="tags[1]" placeholder="タグ&#40;20字まで&#41;" value="{{ $post->tag2 }}"/>
                <input type="text" name="tags[2]" placeholder="タグ&#40;20字まで&#41;" value="{{ $post->tag3 }}"/>
                <input type="text" name="tags[3]" placeholder="タグ&#40;20字まで&#41;" value="{{ $post->tag4 }}"/>
                <input type="text" name="tags[4]" placeholder="タグ&#40;20字まで&#41;" value="{{ $post->tag5 }}"/>
            </div>
            <!--下書き保存作れたらいいな…-->
            <input type="submit" value="投稿する"/>
        </form>
        <div class="footer">
            <a href="/">戻る</a>
        </div>
    </body>
</html>