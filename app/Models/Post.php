<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

//日付を扱うため
use Carbon\Carbon;

class Post extends Model
{
    use HasFactory;
    use softdeletes;
    
    //主キーの設定
    protected $primaryKey = 'id';
    
    //入力可能なカラムの指定
    protected $fillable = [
        'user_id',
        'body',
        'image',
        'tag1',
        'tag2',
        'tag3',
        'tag4',
        'tag5',
    ];
    
    //Userに対するリレーション、posts:user =>多:1
    public function user()
    {
        return $this -> belongsTo(User::class);
    }
    
    //Commentsに対するリレーション post:comments =>1:多
    public function comments()
    {
        return $this -> hasMany(Comment::class, 'parent_post_id');
    }
    
    //Itemsに対するリレーション, posts:items =>多:多
    public function items()
    {
        return $this->belongsToMany(Item::class);
    }
    
    //likesに対するリレーション, post:likes =>1:多
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    
    
    //表示制限、ページネーターはproviders/appserviceに記載
    public function getPaginateByLimit(int $limit_count = 10)
    {
        // updated_atで降順(DESC)に並べる
        //その後limitで件数制限、ページネート
        return $this::with('user')->orderBy('created_at', 'DESC')->paginate($limit_count);
    }
    
    //like数の多い順から並べて、上位を持っていく
    //一か月間バージョン
    public function get_aMonth_popular_posts($limit_count)
    {
        //dd($limit_count);
        
        //carbon::today : 今日が丁度始まった0時00分のインスタンスができる
        //一か月前のデータを取得するとき、subMonth()<->ex.再来月のデータはaddMonth(2)
        $start_date = Carbon::today()->subMonth();
        $end_date = Carbon::today();
        
        //whereBetweenで始まりから終わりまでに作成された投稿の人気順
        //takeでとってくる数指定する
        $aMonth_popular_posts = $this::orderBy('count_likes', 'DESC')
                                    ->whereBetween('created_at', [$start_date, $end_date])
                                    ->take($limit_count)
                                    ->get();
        //dd($aMonth_popular_posts);
        
        return $aMonth_popular_posts;
    }
    
    //like数の多い順から並べて、上位を持っていく
    public function get_Entire_popular_posts($limit_count)
    {
        $entire_popular_posts = $this::orderBy('count_likes', 'DESC')
                                    ->take($limit_count)
                                    ->get();
        //dd($entire_popular_posts);
        
        return $entire_popular_posts;
    }
    
    /**
    * リプライにLIKEを付いているかの判定
    *
    * @return bool true:Likeがついてる false:Likeがついてない
    */
    public function is_liked_by_auth_user()
    {
        $user_id = Auth::id();
        $post_id = $this->id;
        
        return Like::where('user_id', $user_id)->where('post_id', $post_id)->exists();
    }
    
    //フォローしている人の投稿を持ってくる関数
    //[必要な関数]
    //$follow_user_ids : 配列。ログインしているユーザーの"フォローしている人"のIDが入っている。
    //                   Follow.phpのfollow_ids関数で作れる。参考：FollowController
    //$limit_count : 自然数。何個とってくるかを決定する
    public function follows_new_posts(Array $following_user_ids, $limit_count)
    {
        //whereinは配列で検索できる。foreachでいちいち検索する必要なかった
        
        if($limit_count !== null)
        {
            return $this->whereIn('user_id', $following_user_ids)
                    ->orderBy('created_at', 'DESC')
                    ->take($limit_count)
                    ->get();
        }
        else
        {
            return $this->whereIn('user_id', $following_user_ids)
                    ->orderBy('created_at', 'DESC')
                    ->paginate();
        }
    }
}
