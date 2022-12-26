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
    
    /**
     * followeing：フォロー「する」
     * followed：フォロー「される」
     */
        public function follow(Request $request, Follow $follow, User $user)
    {
        $following_user = auth()->id();
        $followed_user = $user->id;
        
        //自分だったらフォローさせない
        if($following_user != $followed_user){
            
            //dd($follow);
            $follow->following_user_id = auth()->id();
            $follow->followed_user_id = $user->id;
        
            dd($follow);
            $follow->save();
            
        }
        
        return back();
    }
    
    public function unfollow(Request $request, Follow $follow, User $user)
    {
        $user_id = auth()->id();
        $followed_user_id = $user->id;
        //dd($user_id);
        //dd($post_id);
        $follow = Follow::where('following_user_id', $user_id)->where('followed_user_id', $followed_user_id)->first();
        
        //dd($follow);
        
        $follow->delete();
        
        return back();
    }
}
