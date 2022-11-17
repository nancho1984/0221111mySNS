<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    
    //主キーの設定
    protected $primaryKey = 'id';
    
    //入力可能なカラムの指定
    protected $fillable = [
        'user_id',
        'parent_post_id',
        'parent_comment_id',
        'child_comment_id',
        'body',
    ];
    
    //Userに対するリレーション、comments:user=>多:1
    public function user()
    {
        return $this -> belongsTo(User::class);
    }
    
    //Postに対するリレーション、comment:post =>多:1
    public function post()
    {
        return $this -> belongsTo(Post::class);
    }
}
