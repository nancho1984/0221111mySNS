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
            <!--ここのエラー治ったらプロフ画像も直して-->
            <div class="image">
                
                <input 
                    type="file"
                    name="post[image]"
                    accept=".gif, .jpg, .png">
            </div>
            
            <div class="items">
                <!-- アイテムを追加ボタンを押したら、次のフォームが出るようにすること-->
                <p>アイテム</p>
                <p>使用したアイテムだけでなく、参考にしたサイトのURLもあると、他の人も参考にしやすい投稿になります！</p>
                <input type="text" name="items[URL][0]" placeholder="アイテムのURL" value="{{ old('items.URL.0') }}"/><br>
                <input type="text" name="items[URL][1]" placeholder="アイテムのURL" value="{{ old('items.URL.1') }}"/><br>
                <input type="text" name="items[URL][2]" placeholder="アイテムのURL" value="{{ old('items.URL.2') }}"/><br>
                <input type="text" name="items[URL][3]" placeholder="アイテムのURL" value="{{ old('items.URL.3') }}"/><br>
                <input type="text" name="items[URL][4]" placeholder="アイテムのURL" value="{{ old('items.URL.4') }}"/><br>
                <input type="text" name="items[URL][5]" placeholder="アイテムのURL" value="{{ old('items.URL.5') }}"/><br>
                <input type="text" name="items[URL][6]" placeholder="アイテムのURL" value="{{ old('items.URL.6') }}"/><br>
                <input type="text" name="items[URL][7]" placeholder="アイテムのURL" value="{{ old('items.URL.7') }}"/><br>
                <input type="text" name="items[URL][8]" placeholder="アイテムのURL" value="{{ old('items.URL.8') }}"/><br>
                <input type="text" name="items[URL][9]" placeholder="アイテムのURL" value="{{ old('items.URL.9') }}"/><br>
            </div>
            
            <div class="body">
                <p>説明</p>
                <textarea name="post[body]" placeholder="&#40;500字まで&#41;">{{ old('post.body')}}</textarea>
            </div>
            <div class="tags">
                <p>タグ</p>
                <!-- タグを追加ボタンを押したら、次のフォームが出るようにすること-->
                <input type="text" name="post[tag1]" placeholder="タグ1&#40;20字まで&#41;" value="{{ old('post.tag1') }}"/>
                <input type="text" name="post[tag2]" placeholder="タグ2&#40;20字まで&#41;" value="{{ old('post.tag2') }}"/>
                <input type="text" name="post[tag3]" placeholder="タグ3&#40;20字まで&#41;" value="{{ old('post.tag3') }}"/>
                <input type="text" name="post[tag4]" placeholder="タグ4&#40;20字まで&#41;" value="{{ old('post.tag4') }}"/>
                <input type="text" name="post[tag5]" placeholder="タグ5&#40;20字まで&#41;" value="{{ old('post.tag5') }}"/>
            </div>
            <!--下書き保存作れたらいいな…-->
            <input type="submit" value="投稿する"/>
        </form>
        <div class="footer">
            <a href="/">戻る</a>
        </div>
    </body>
</html>