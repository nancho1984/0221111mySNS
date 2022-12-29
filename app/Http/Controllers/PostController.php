<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Models\Item;
use App\Models\Like;
use App\Models\Notification;

use App\Http\Requests\PostRequest;
use App\Http\Requests\PostUpdateRequest;
use Cloudinary;

class PostController extends Controller
{
    public function showTop(Post $post, User $user)
    {
        //blade内で使う変数'posts'と設定。'posts'の中身にgetを使い、インスタンス化した$postを代入。
        //getPaginateByLimit()はpost.php参照
        $user = Auth::user();
        $number_notices = "";
        
        //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        // $count_noticeについて
        // いったん通知のテストのために仮置きしている変数です。あとで消してね
        
        //dd($user);
        if($user != NULL)
        {
            //ユーザーがログインしてるとき
            $search_notice = Notification::where('user_id', $user->id)
                ->where('read_at', NULL);
            //カウントかえす
            $number_notices = $search_notice->count();
            //内容を3件だけわたす
            $notifications = $search_notice->limit(3);
            
            //その人本人が「いいね」押してるか検索
            $like=Like::where('post_id', $post->id)->where('user_id', $user->id)->first();
            //dd($like);
            
        }else{
            //ログインしてないとき
            $number_notices = NULL;
            $notifications = NULL;
            $like = NULL;
        }
        //dd($notifications);
        
        //もともとは30
        $new_posts = $post->getPaginateByLimit(5);
                
        return view('timeline',compact(
            'user',
            'like',
            'new_posts',
            
            //あとで通知のやつ消してね
            'number_notices',
            'notifications',
            ));
    }
    
    public function showNewPosts(Post $post, User $user)
    {
        //blade内で使う変数'posts'と設定。'posts'の中身にgetを使い、インスタンス化した$postを代入。
        //getPaginateByLimit()はpost.php参照
        $user = Auth::user();
        $number_notices = "";
        
        //dd($notifications);
        
        $new_posts = $post->getPaginateByLimit(30);
        
        
                
        return view('show_newposts',compact(
            'user',
            'new_posts',
            ));
    }
    
    public function showUsersPosts(Post $post, User $user)
    {
        $post = $user->posts()->paginate(10);
        //->orderBy('created_at', 'DESC')->paginate(10)->get();
        
        if($user != NULL)
        {
            //その人本人が「いいね」押してるか検索
            $like=Like::where('post_id', $post->id)->where('user_id', auth()->user()->id)->first();
            //dd($like);
            
        }
        
        return view('show_userspost')->with([
            'posts' => $post,
            'user' => $user,
            'like' => $like,
            ]);
    }
    
    //ユーザーがいいねを押した投稿
    public function showPostsLiked(Post $post, User $user)
    {
        //ユーザーが「いいね」押した投稿検索
        $liked_posts=Like::where('user_id', $user->id)
            ->orderBy('created_at', 'DESC')
            ->paginate(30);
        
        return view('show_postsliked',compact('liked_posts'));
    }
    
    /**
    * 特定IDのpostを詳細表示する
    *
    * @params Object Post // 引数の$postはid=1のPostインスタンス
    * @return Reposnse post view
    */
    public function showPost(Post $post)
    {
        //フォローに使う用、投稿したユーザーのデータ
        $writer_user = User::where('id', $post->user_id)->first();
        $reader_user = Auth::user();
        //dd($reader_user);
        
        
        //postモデルのcommentsメソッドでコメント呼び出し
        $comments = $post->comments;
        //dd($comments);
        
        //Itemをよぶ
        $items = $post->items;
        
        return view('show')->with([
            'post' => $post,
            'writer_user' => $writer_user,
            'reader_user' => $reader_user,
            'comments' => $comments,
            'items' => $items,
        ]);
        //'post'はbladeファイルで使う変数。中身は$postはid=1のPostインスタンス。
    }
    
    public function create(Post $post)
    {
        return view('create');
    }
    
    public function store(PostRequest $request, Post $post, Item $item)
    {
        
        //dd($request->all());
        
        //request[]でDBから「型(キー)」をもらってinputをつめる
        $input_post = $request['post'];
        $input_URLs = $request['items']['URL'];
        
        $sizechecker = $request->file('post.image')->getSize();
        //sizeが1MB以上なら元居たページに戻す
        
        $post->fill($input_post);
        $post->image = Cloudinary::upload($request->file('post.image')->getRealPath())->getSecurePath();
        
        //ポストを保存
        $post->user_id = auth()->id();
        $post->save();
        
        //インプット内のそれぞれのURLに対して作業
        foreach($input_URLs as $input_URL){
            //値はあるかチェック
            if(empty($input_URL)){
                
            } else {
                //URLの重複チェック。すでにある時は保存しない
                if (Item::where('URL', $input_URL)->exists()){
            
                } else {
                    //保存。fillだと一個しか登録できない
                    Item::create(['URL'=>$input_URL]);
                }
        
                //中間テーブルに保存
                //まずitemのidをとってくる。
                //もしも複数同じものがあった時エラーを起こさない用firstメソッド
                $item_withid = Item::where('URL', $input_URL)->first();
                //itemに結びついたpostのidを呼んで、入れてsyncで保存
                //attachはダブりあり、syncは同じidの結びつきは一個だけ
                $item_withid->Posts()->attach($post->id);   
            }
         
        }
        
        return redirect('/posts/' . $post->id);
    }
    
    public function edit(Post $post)
    {
        //Itemをよぶ
        $items = $post->items;
        $count_items = count($items);
        
        return view('edit')->with([
            'post' => $post,
            'items' => $items,
            'count_items' => $count_items,
            ]);
    }
    
    public function update(PostUpdateRequest $request, Post $post)
    {
        //dd($request->all());
        $input = $request['postupdate'];
        $input_URLs = $request['items']['URL'];
        
        //もともとあったURLをしらべる
        $DBURLs = $post->items()->get(["URL"]);
        //dd($DBURLs);
        
        $post->fill($input);
        
        //画像が入っている＝新しい画像に変更されたときのみ保存
        //dd($request->file('postupdate.image'));
        if($request->file('postupdate.image'))
        {
            $post->image = Cloudinary::upload($request->file('postupdate.image')->getRealPath())->getSecurePath();
        }
        
        //dd($post->image);
        $post->user_id = auth()->id();
        $post->save();
        
        
        //アイテムの保存について
        //前までに保存されてるURLをとってくる
        $before_URLs = [];
        foreach($DBURLs as $DBURL)
        {
            array_push($before_URLs, $DBURL->URL); 
        }
        //dd($before_URLs);
        
        
        /**
         * このforeachでやること：前にあったけど今ないやつを削除する。
         * 
         * array_diff(前回のURL配列 - 今回のURL配列(input) = 「前回」はあったけど今回はなくなったURL)
         */
        foreach(array_diff($before_URLs, $input_URLs) as $deleted_URL)
        {
            //getだとコレクションになってしまう
            $deleted_item = Item::where('URL', $deleted_URL)->first();
            //dd($deleted_item);
            $deleted_item->Posts()->detach($post->id);
        }
        
        /**
         * このforeachでやること：前なかったやつを登録する。
         * 
         * Itemsに登録されて「ない」ときは、Itemsに登録、関係も登録(中間テーブル)。
         * Itemsに登録されて「いる」ときは、関係だけ登録。
         * 
         * array_diff(今回のURL配列(input) - 前回のURL配列 = 前回はなかったけど「今回」はあるURL)
         */
         
        //dd(array_diff($input_URLs, $before_URLs));
        foreach(array_diff($input_URLs, $before_URLs) as $new_URL)
        {
            //空欄の値を持ってきてないか
            if($new_URL !== null)
            {
                //URLはすでにItemテーブルに存在するか？
                if(Item::where('URL', $new_URL)->exists())
                {
                    
                } else {
                    //保存。fillだと一個しか登録できない
                    //dd($new_URL);
                    Item::create(['URL'=>$new_URL]);
        
                    //中間テーブルに保存
                    //まずitemのidをとってくる。
                    //もしも複数同じものがあった時エラーを起こさない用firstメソッド
                    //attachはダブりあり、syncは同じidの結びつきは一個だけ
                    $item_withid = Item::where('URL', $new_URL)->first();
                    //itemに結びついたpostのidを呼んで、入れてsyncで保存
                    $item_withid->Posts()->attach($post->id); 
                }
            }
        }
        
        return redirect('/posts/' . $post->id);
    }
    
    /**
     * タグのURLで検索するやつ
     */
    public function ItemSearch(Item $item, Post $post)
    {
        
        //$itemはリクエストから持ってきたitemのインスタンス
        //posts()はリレーションメソッド
        
        //dd($item->posts()->paginate(10));
        $posts = $item->posts()->paginate(10);
        
        
        //見ているユーザー本人が「いいね」押してるか検索
        $like=Like::where('post_id', $post->id)->where('user_id', auth()->user()->id)->first();
        
        return view('show_item')->with([
            'posts' => $posts,
            'item' => $item,
            'like' => $like,
            ]);
    }
    
    /**
     * 投稿を検索ボックスで検索するやつ('投稿の内容'で検索するやつ)
     */
    public function searchbarPosts(Request $request)
    {
        
        if(!($request->filled('search_posts')))
        {
            return back();
        }
        
        $posts = Post::paginate(30);
        $search = $request->input('search_posts');
        
        //検索フォームに値がある(入力されてる)かどうか
        if($search)
        {
            //全角スペースを半角スペースに変換
            $searchPhrase = mb_convert_kana($search, 's');
            
            //単語を半角スペースずつで配列化する
            //"三島 美輪明宏 肩パッド"を["三島", "美輪明宏", "肩パッド"]に
            $searchWords = preg_split('/[\s,]+/', $searchPhrase, -1, PREG_SPLIT_NO_EMPTY);
            
             //カラ配列作る
            $hit_ids=array();
            
            //dd($searchWords);
            //単語ずつで検索
            foreach($searchWords as $key=>$word)
            {
                
                //if($key===1) dd($query->get());
                
                //LIKE検索、本文、タグ
                $result=Post::where('body', 'LIKE', '%'.$word.'%')
                    ->orwhere('tag1', 'LIKE', '%'.$word.'%')
                    ->orwhere('tag2', 'LIKE', '%'.$word.'%')
                    ->orwhere('tag3', 'LIKE', '%'.$word.'%')
                    ->orwhere('tag4', 'LIKE', '%'.$word.'%')
                    ->orwhere('tag5', 'LIKE', '%'.$word.'%')
                    ->pluck('id')->toArray();
                
                //見つかったidの配列を、unpackして$hit_idに格納
                array_push($hit_ids, ...$result);
            }
            
            //$hit_idsで見つけたidを全検索する。
            $posts= Post::whereIn('id', $hit_ids)->paginate(30);
        }
        
        //dd($searchPhrase);
        return view('search_posts',compact('searchPhrase', 'posts'));
    }
    
    public function delete(Post $post)
    {
        $post->delete();
        return redirect('/');
    }
}
