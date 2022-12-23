<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}
