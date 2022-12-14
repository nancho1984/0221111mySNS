<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    
    //主キーの設定
    protected $primaryKey = 'id';
    
    //入力可能なカラムの指定
    protected $fillable = [
        'URL',
        'og_title',
        'og_image',
    ];
    
    //Postsに対するリレーション, items:posts =>多:多
    public function Posts()
    {
        return $this->belongsToMany(Post::class)
                ->withTimestamps();
    }
}
