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

use App\Http\Controllers\ItemController;
use App\Http\Controllers\FollowController;

use App\Http\Requests\PostRequest;
use App\Http\Requests\PostUpdateRequest;
use Cloudinary;

class PostController extends Controller
{
    public function test2(Post $post)
    {
        //フォローに使う用、投稿したユーザーのデータ
        $writer_user = User::where('id', $post->user_id)->first();
        $reader_user = Auth::user();
        //dd($reader_user);
        
        $search_URLs = ItemController::pass_converted_URLs($post);
        
        //dd($search_URLs);
        
        //postモデルのcommentsメソッドでコメント呼び出し
        $comments = $post->comments;
        //dd($comments);
        
        return view('test2')->with([
            'post' => $post,
            'writer_user' => $writer_user,
            'reader_user' => $reader_user,
            'comments' => $comments,
            'items' => $search_URLs['items'],
            'references' => $search_URLs['references'],
        ]);
    }
    
    public function showTop(Post $post, User $user)
    {
        //--------------------------
        //投稿をとってくる処理
        //--------------------------
        
        //[みんなの新着投稿]
        //新着の中でもトップの3つをとってくる
        $new_posts = Post::orderBy('created_at', 'DESC')
                    ->take(3)
                    ->get();
                    
        //[フォロー新着投稿]
        //フォローしている人の新着の中でも最新のいくつかをとってくる
        //投稿を全部持ってこられると困るので、３つだけモラウ
        
        $auth_user = Auth::user();
        
        $limit_count = 3;
        
        //フォロー機能はログインしている人限定なので
        //ログインしてるか確認
        if($auth_user !== null)
        {
            $follows_new_posts = FollowController::get_follows_posts($auth_user, $limit_count);
            //dd($follows_new_posts);
        }
        else
        {
            //ログインしてないとき
            $follows_new_posts = null;
        }
                    
        //[人気の投稿]
        //今の旬のコーデを知りたいと思うので、topでは「月間の」人気投稿を表示する
        //人気の投稿を全部持ってこられると困るので、３つだけモラウ
        $limit_count = 3;
        
        $popular_posts = $post->get_aMonth_popular_posts($limit_count);
        
        //もし月間の人気投稿が一個もない場合、全期間の人気投稿をもらってくる
        if(count($popular_posts) === 0)
        {
            $popular_posts = $post->get_Entire_popular_posts($limit_count);
        }
        
        
        
        //dd($popular_users);
                
        return view('timeline',compact(
            'auth_user',
            
            'new_posts',
            'popular_posts',
            'follows_new_posts'
            
            ));
    }
    
    public function showFollowsPosts(Post $post, User $user)
    {
        //--------------------------
        //投稿をとってくる処理
        //--------------------------
        
        //フォローしている人の投稿をs新しい順で持ってくる
        
        $auth_user = Auth::user();
        
        //ページネートでほしいのでカウントはNULLを指定
        $limit_count = null;
        
        //フォロー機能はログインしている人限定なので
        //ログインしてるか確認
        if($auth_user !== null)
        {
            $follows_new_posts = FollowController::get_follows_posts($auth_user, $limit_count);
            //dd($follows_new_posts);
        }
        else
        {
            //ログインしてないとき
            $follows_new_posts = null;
        }
                
        return view('show_follows_posts',compact(
            'auth_user',
            
            'follows_new_posts'
            
            ));
    }
    
    //1か月の間で人気だった投稿をもっていく関数
    //ページネートせず、30個だけ持ってくる
    public function showMonthPopularPosts(Post $post, User $user)
    {
        $user = Auth::user();
        
        $month_popular_posts = $post->get_aMonth_popular_posts(30);
        
        return view('show_month_popular_posts',compact(
            'user',
            'month_popular_posts',
            ));
    }
    
    //すべての期間を通して人気だった投稿をもっていく関数
    //ページネートせず、30個だけ持ってくる
    public function showEntirePopularPosts(Post $post, User $user)
    {
        $user = Auth::user();
        
        $entire_popular_posts = $post->get_Entire_popular_posts(30);
        
        return view('show_entire_popular_posts',compact(
            'user',
            'entire_popular_posts',
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
        $post = $user->posts()->paginate(30);
        //->orderBy('created_at', 'DESC')->paginate(10)->get();
        
        return view('show_userspost')->with([
            'posts' => $post,
            'user' => $user,
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
        
        $search_URLs = ItemController::pass_converted_URLs($post);
        
        //dd($search_URLs);
        
        //postモデルのcommentsメソッドでコメント呼び出し
        $comments = $post->comments;
        //dd($comments);
        
        return view('show')->with([
            'post' => $post,
            'writer_user' => $writer_user,
            'reader_user' => $reader_user,
            'comments' => $comments,
            'items' => $search_URLs['items'],
            'references' => $search_URLs['references'],
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
        $input_items = array_unique($request['items']);
        $input_references = array_unique($request['references']);
        
        //dd($input_items);
        
        $sizechecker = $request->file('post.image')->getSize();
        //sizeが1MB以上なら元居たページに戻す
        
        $post->fill($input_post);
        
        //タグを保存していく
        
        //タグ入力フォームのそれぞれの値を確認
        foreach($request['tags'] as $tag)
        {
            //値がからじゃ「ない」か確認
            if($tag !== null){
                //タグ1がまだ「ない」とき
                if(!$post->tag1)
                {
                    //dd($tag);
                    $post->tag1 = $tag;
                }
                //タグ1は「ある」けど、タグ2がまだ「ない」とき
                elseif(!$post->tag2)
                {
                    //dd($tag);
                    $post->tag2 = $tag;
                }
                //タグ2は「ある」けど、タグ3がまだ「ない」とき
                elseif(!$post->tag3)
                {
                    //dd($tag);
                    $post->tag3 = $tag;
                }
                //タグ3は「ある」けど、タグ4がまだ「ない」とき
                elseif(!$post->tag4)
                {
                    //dd($tag);
                    $post->tag4 = $tag;
                }
                //タグ4は「ある」けど、タグ5がまだ「ない」とき
                elseif(!$post->tag5)
                {
                    //dd($tag);
                    $post->tag5 = $tag;
                }
            }
        }
        
        //dd($post);
        $post->image = Cloudinary::upload($request->file('post.image')->getRealPath())->getSecurePath();
        
        //ポストを保存
        $post->user_id = auth()->id();
        $post->save();
        
        ItemController::store_items($input_items, $post);
        
        ItemController::store_references($input_references, $post);
        
        return redirect('/posts/' . $post->id);
    }
    
    public function edit(Post $post)
    {
        $search_URLs = ItemController::pass_converted_URLs($post);
        
        $count_references = count($search_URLs['references']);
        $count_items = count($search_URLs['items']);
        
        return view('edit')->with([
            'post' => $post,
            'items' => $search_URLs['items'],
            'references' => $search_URLs['references'],
            'count_items' => $count_items,
            'count_references' => $count_references,
            ]);
    }
    
    public function update(PostUpdateRequest $request, Post $post)
    {
        //dd($request->all());
        $input = $request['postupdate'];
        $input_items = array_unique($request['items']);
        $input_references = array_unique($request['references']);
        $before_URLs = ItemController::pass_converted_URLs($post);
        
        //[タグの更新・保存]
        //array_filterは値があるものだけの配列にする
        //array_valuesでfilterで歯抜けになった番号を付けなおす
        $input_tags = array_values(array_filter($request['tags']));
        $tag_counter = count($input_tags);
        //カラムの使用上、空欄も含めて5個の配列でないといけないので空欄追加
        for($i = 0; $i < 5-$tag_counter; $i++)
        {
            $input_tags[] = null;
        }
        //タグを更新していく
        //問答無用で5回繰り返す
        //「値がある」ものは前の番号で保存しきって、「値がない」ものは後ろへ
        for($i = 0; $i < 5; $i++)
        {
            //$tag:繰り返しの中で使う便宜的変数
            $tag = $input_tags[$i];
            
            if($i === 0)
            {
                //1回目のループ
                Post::where('id', $post->id)->update([
                    'tag1' => $tag,
                    ]);
            }
            elseif($i === 1)
            {
                //2回目のループ
                Post::where('id', $post->id)->update([
                    'tag2' => $tag,
                    ]);
            }
            elseif($i === 2)
            {
                //3回目のループ
                Post::where('id', $post->id)->update([
                    'tag3' => $tag,
                    ]);
            }
            elseif($i === 3)
            {
                //4回目のループ
                Post::where('id', $post->id)->update([
                    'tag4' => $tag,
                    ]);
            }
            elseif($i === 4)
            {
                //5回目のループ
                Post::where('id', $post->id)->update([
                    'tag5' => $tag,
                    ]);
            }
        }
        //dd(Post::where('id', $post->id)->first());
        
        //[画像の保存]
        $post->fill($input);
        //画像が入っている＝新しい画像に変更されたときのみ保存
        //dd($request->file('postupdate.image'));
        if($request->file('postupdate.image'))
        {
            $post->image = Cloudinary::upload($request->file('postupdate.image')->getRealPath())->getSecurePath();
        }
        
        //dd($post->image);
        $post->user_id = auth()->id();
        
        //ポストの項目はすべて済ませたのでいったん保存
        //dd($post);
        $post->save();
        
        //[URLの保存]
        ItemController::update_items($input_items, $before_URLs, $post);
        
        ItemController::update_references($input_references, $before_URLs, $post);
        
        return redirect('/posts/' . $post->id);
    }
    
    /**
     * タグのURLで検索するやつ
     */
    public function ItemSearch(Item $item, Post $post)
    {
        
        //$itemはリクエストから持ってきたitemのインスタンス
        //posts()はリレーションメソッド
        
        //dd($item);
        
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
