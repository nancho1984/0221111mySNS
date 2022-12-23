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
        <form action="/posts/{{ $post->id }}" method="POST">
            @csrf
            @method('PUT')
            <div class="image">
                <input 
                    type="file" 
                    name="image" 
                    accept=".gif, .jpg, .png">
                    
                <p class="image__error" style="color:red">{{ $errors->first('post.image') }}</p>
            </div>
            <div class="items">
                <!-- アイテムを追加ボタンを押したら、次のフォームが出るようにすること-->
                <p>使用/参考にしたアイテム</p>
                <input type="text" name="items[URL][0]" placeholder="アイテムのURL" /><br>
                <input type="text" name="items[URL][1]" placeholder="アイテムのURL" /><br>
                <input type="text" name="items[URL][2]" placeholder="アイテムのURL" /><br>
                <input type="text" name="items[URL][3]" placeholder="アイテムのURL" /><br>
                <input type="text" name="items[URL][4]" placeholder="アイテムのURL" /><br>
                <input type="text" name="items[URL][5]" placeholder="アイテムのURL" /><br>
                <input type="text" name="items[URL][6]" placeholder="アイテムのURL" /><br>
                <input type="text" name="items[URL][7]" placeholder="アイテムのURL" /><br>
                <input type="text" name="items[URL][8]" placeholder="アイテムのURL" /><br>
                <input type="text" name="items[URL][9]" placeholder="アイテムのURL" /><br>
            </div>
            <div class="body">
                <p>説明</p>
                <textarea name="post[body]" placeholder="&#40;500字まで&#41;">{{ $post->body }}</textarea>
                <p class="body__error" style="color:red">{{ $errors->first('post.body') }}</p>
            </div>
            <div class="tags">
                <p>タグ</p>
                <!-- タグを追加ボタンを押したら、次のフォームが出るようにすること-->
                <input type="text" name="post[tag1]" placeholder="タグ1&#40;20字まで&#41;" value="{{ $post->tag1 }}"/>
                <input type="text" name="post[tag2]" placeholder="タグ2&#40;20字まで&#41;" value="{{ $post->tag2 }}"/>
                <input type="text" name="post[tag3]" placeholder="タグ3&#40;20字まで&#41;" value="{{ $post->tag3 }}"/>
                <input type="text" name="post[tag4]" placeholder="タグ4&#40;20字まで&#41;" value="{{ $post->tag4 }}"/>
                <input type="text" name="post[tag5]" placeholder="タグ5&#40;20字まで&#41;" value="{{ $post->tag5 }}"/>
                <p class="tag1__error" style="color:red">{{ $errors->first('post.tag1') }}</p>
                <p class="tag2__error" style="color:red">{{ $errors->first('post.tag2') }}</p>
                <p class="tag3__error" style="color:red">{{ $errors->first('post.tag3') }}</p>
                <p class="tag4__error" style="color:red">{{ $errors->first('post.tag4') }}</p>
                <p class="tag5__error" style="color:red">{{ $errors->first('post.tag5') }}</p>
            </div>
            <!--下書き保存作れたらいいな…-->
            <input type="submit" value="投稿する"/>
        </form>
        <div class="footer">
            <a href="/">戻る</a>
        </div>
    </body>
</html>