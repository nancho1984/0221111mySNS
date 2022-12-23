<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Comment extends Model
{
    use HasFactory;
    
    //主キーの設定
    protected $primaryKey = 'id';
    
    //入力可能なカラムの指定
    protected $fillable = [
        'user_id',
        'body',
        'parent_post_id',
        'reply_user_id',
    ];
    
    //Userに対するリレーション、comments:user=>多:1
    public function user()
    {
        return $this -> belongsTo(User::class);
    }
    
    //Postに対するリレーション、comment:post =>多:1
    public function post()
    {
        return $this -> belongsTo(Post::class, 'parent_post_id');
    }
    
    public function reply_user()
    {
        return $this -> belongsTo(User::class, 'reply_user_id');
    }
    
}
