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
        <h2>プロフィール編集</h2>
        
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
        
        <form action="/users/{{ $user->id }}/update" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class='image'>
                <img src="{{ $user->image }}" width="100" height="100">
            </div>
            <p>プロフィール画像を編集する</p>
            <div class="image">
                
                <input 
                    type="file"
                    name="profile[image]"
                    accept=".gif, .jpg, .png">
            </div>
            
            <div class="addressname">
                <span>
                    <p>ユーザーID　</p>
                    <input type="text" name="profile[addressname]" value="{{ $user->addressname }}"/><br>
                </span>
                <p>Kitemite内で使われるアドレスです。既に使われているものは登録できません</p>
            </div>
            
            <div class="nickname">
                <span>
                    <p>ニックネーム　</p>
                    <input type="text" name="profile[nickname]" value="{{ $user->nickname }}"/><br>
                </span>
            </div>
            
            <div class="profile_sentence">
                <p>自己紹介文</p>
                <textarea name="profile[profile_sentence]" placeholder="&#40;5000字まで&#41;">{{ $user->profile_sentence }}</textarea>
            </div>
            
            <input type="submit" value="変更する"/>
        </form>
    </body>
</html>