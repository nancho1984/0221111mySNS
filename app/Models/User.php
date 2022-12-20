<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    
    //入力可能なカラムの指定
    protected $fillable = [
        'name',
        'image',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    //Postに対するリレーション, user:posts=>1:多
    public function posts()
    {
        return $this -> hasMany(Post::class);
    }
    
    //Commentに対するリレーション, user:comments=>1:多
    public function comments()
    {
        return $this -> hasMany(Comment::class);
    }
    
    //likesに対するリレーション, users:likes =>1:多
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    
    //followsに対するリレーション, user:follows =>1:多
    public function follows()
    {
        return $this->hasMany(Follow::class);
    }
    
    //ユーザーが自分をフォローしないための本人確認チェック
    public function they_isnt_auth_user()
    {
        $user_id = Auth::id();
        $followed_id = $this->id;
        
        return $user_id != $followed_id;
    }
    
    //すでにフォローしているユーザーがチェック
    public function is_followed_by_auth_user()
    {
        $user_id = Auth::id();
        $followed_id = $this->id;
        
        return Follow::where('following_user_id', $user_id)->where('followed_user_id', $followed_id)->exists();
    }
    
}
