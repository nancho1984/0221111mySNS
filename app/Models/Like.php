<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;
    public $timestamps = false;
    
    //主キーの設定
    protected $primaryKey = 'id';
    
    //入力可能なカラムの指定
    protected $fillable = [
        'user_id',
        'post_id',
    ];
    
    //usersに対するリレーション, user:likes =>1:多
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    //postsに対するリレーション, post:likes =>1:多
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
