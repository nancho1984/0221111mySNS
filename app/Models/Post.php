<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    
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
        return $this -> hasMany(Comment::class);
    }
    
    //Itemsに対するリレーション, posts:items =>多:多
    public function Posts()
    {
        return $this->belongsToMany(Item::class);
    }
    
}
