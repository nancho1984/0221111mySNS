<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Models\Item;
use App\Models\Like;

use App\Http\Requests\PostRequest;
use Cloudinary;

class PostController extends Controller
{

    
    public function showHome(Post $post, User $user)
    {
        //blade内で使う変数'posts'と設定。'posts'の中身にgetを使い、インスタンス化した$postを代入。
        //getPaginateByLimit()はpost.php参照
        $user = Auth::user();
        
        //その人本人が「いいね」押してるか検索
        $like=Like::where('post_id', $post->id)->first();
        //dd($like);
        
        return view('timeline')
        ->with(['user'=> $user,
                'like'=> $like,
                'posts' => $post->getPaginateByLimit()]);
    }
    
    public function showUsersPosts(Post $post, User $user)
    {
        $post = $user->posts()->paginate(10);
        //->orderBy('created_at', 'DESC')->paginate(10)->get();
        
        //その人本人が「いいね」押してるか検索
        $like=Like::where('post_id', $post->id)->where('user_id', auth()->user()->id)->first();
        
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
    * 特定IDのpostを表示する
    *
    * @params Object Post // 引数の$postはid=1のPostインスタンス
    * @return Reposnse post view
    */
    public function showPost(Post $post, User $user)
    {
        //フォローに使うためのユーザーデータ
        $user = User::where('id', $post->user_id)->first();
        
        //postモデルのcommentsメソッドでコメント呼び出し
        $parent_post = $post;
        $comments = $parent_post->comments;
        //dd($comments);
        
        //Itemをよぶ
        $items = $post->items;
        
        //その人本人が「いいね」押してるか検索
        $like=Like::where('post_id', $post->id)->where('user_id', auth()->user()->id)->first();
        
        return view('show')->with([
            'post' => $post,
            'user' => $user,
            'comments' => $comments,
            'items' => $items,
            'like' => $like,
        ]);
        //'post'はbladeファイルで使う変数。中身は$postはid=1のPostインスタンス。
    }
    
    public function create(Post $post)
    {
        return view('create');
    }
    
    public function store(PostRequest $request, Post $post, Item $item)
    {
        dd($request->all());
        
        
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
        return view('edit')->with(['post' => $post]);
    }
    
    public function update(PostRequest $request, Post $post)
    {
        $input = $request['post'];
        $input_URLs = $request['items']['URL'];
        //これも同じものならアップロード止めたい
        $post->image = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
        $post->user_id = auth()->id();
        $post->fill($input)->save();
        
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
                //attachはダブりあり、syncは同じidの結びつきは一個だけ
                $item_withid = Item::where('URL', $input_URL)->first();
                //itemに結びついたpostのidを呼んで、入れてsyncで保存
                $item_withid->Posts()->attach($post->id);   
            }
         
        }

        return redirect('/posts/' . $post->id);
    }
    
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
    
    public function delete(Post $post)
    {
        $post->delete();
        return redirect('/');
    }
}
