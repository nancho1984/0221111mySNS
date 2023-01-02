<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'following_user_id',
        'followed_user_id'
    ];
    
    public $timestamps = false;
    
    //usersに対するリレーション, user:follows =>1:多
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    //show_followusersで使用
    public function follow_user()
    {
        return $this->belongsTo(User::class, 'following_user_id');
    }
    
    public function follower()
    {
        return $this->belongsTo(User::class);
    }
    
    //ユーザーIDからフォローしてる人をたどるとき使う
    public function follow_ids($auth_user)
  {
      return $this->where('following_user_id', $auth_user->id)->get();
  }
}
