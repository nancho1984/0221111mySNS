<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
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
}
