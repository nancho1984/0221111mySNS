<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\User;
use App\Models\Follow;
use App\Models\Like;
use App\Models\Comment;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use HasFactory;
    public $timestamps = false;
    
    //主キーの設定
    //protected $primaryKey = 'id';
    
    //入力可能なカラムの指定
    protected $fillable = [
        'user_id',
        'type_id',
        'type',
        'agent_id',
        'read_at',
    ];
    
    //usersに対するリレーション, user:likes =>1:多、後ろに
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function convert_notices($notification, $type, $target, $convert)
    {
        //何も値が入ってない状況でないか
        if(!$convert_notices)
        {
            //値がなにもなかったとき = 1番目のデータ
            dd("convert_noticesはからっぽ");
            $post_ids = array();
            $agents = array();
            
            
            array_push($convert_notices, [
                'type' => $type,
                'notices_id' => 1,
                'post_id' => $target->post_id,
                'agents' => $agents,
                'count' => 1,
                ]);
        }
        else
        {
            //$convert_noticesにすでに値があるとき
            dd("アタイがいるよ！！");
            
            //$convert_noticesのインデックス番号を検索
            //$post_idと$typeで検索をかける
               
            
            $index_number = array_search($target->post_id, $post_ids);
        }    
        
    }
}
