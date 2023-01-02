<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use App\Models\Follow;
use App\Models\Notification;
use App\Http\Controllers\NotificationController;

class FollowController extends Controller
{
    public function show_followusers(User $user)
    {
        
        //検索用サーチ変数
        $followusers_count = count(Follow::where('following_user_id', $user->id)->get());
        
        $follows = Follow::where('following_user_id', $user->id)->paginate(40);
        
        //dd($follows);
        return view('show_followusers',compact('follows', 'followusers_count'));
    }
    
    public function show_followers(User $user)
    {
        $followers_count = count(Follow::where('followed_user_id', $user->id)->get());
        
        $followers = Follow::where('followed_user_id', $user->id)->paginate(40);
        
        return view('show_followers',compact('followers', 'followers_count'));
    }
    
    //認証済みのユーザーがフォローしている人の新しい投稿を入手する
    //[必要な変数]
    //$auth_user : ログインしているユーザーのインスタンス
    //$limit_count : もし限られた個数必要であれば数字を入れる。
    //               ページネートでほしい場合はnullを入れておいてください。
    public static function get_follows_posts($auth_user, $limit_count)
    {
        $follow = new Follow;
        $post = new Post;
        
        $follow_ids = $follow->follow_ids($auth_user);
        //auth_userが「フォローしている」人のIDだけの配列に変える
        $following_user_ids = $follow_ids->pluck('followed_user_id')->toArray();
        
        $follows_new_posts = $post->follows_new_posts($following_user_ids, $limit_count);
        
        return $follows_new_posts;
    }
}
