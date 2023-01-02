<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use App\Models\Like;

class LikeController extends Controller
{
    
    public function like(Request $request, Like $like, Post $post)
    {
        $like->user_id = auth()->id();
        $like->post_id = $post->id;
        
        //dd($like);
        
        $like->save();
        
        //postの人気度をはかるためのカウントを1つ「増やす」
        $post->count_likes ++;

        $post->save();
        
        //通知を送る
        NotificationController::like_notice($like);
        
        return back();
    }
    
    public function unlike(Request $request, Like $like, Post $post)
    {
        $user_id = auth()->id();
        $post_id = $post->id;
        //dd($user_id);
        //dd($post_id);
        $like = Like::where('user_id', $user_id)->where('post_id', $post_id)->first();
        
        //postの人気度をはかるためのカウントを1つ「減らす」
        $post->count_likes --;

        $post->save();
        
        //通知を消す用(既読済みなら消さない)
        NotificationController::delete_like_notice($like);
        
        $like->delete();
        
        return back();
    }
}
