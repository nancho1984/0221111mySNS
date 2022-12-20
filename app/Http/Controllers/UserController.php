<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Follow;
use App\Models\Post;
use App\Models\User;
use Cloudinary;

class UserController extends Controller
{
    public function showProfile(User $user)
    {
        $posts = $user->posts()->paginate(10);
        $likes = $user->likes()->paginate(10);
        $followusers_count = count(Follow::where('following_user_id', $user->id)->get());
        $followers_count = count(Follow::where('followed_user_id', $user->id)->get());
        
        return view('profile')->with([
            'user' => $user, 
            'posts' => $posts,
            'likes' => $likes,
            'followusers_count' => $followusers_count,
            'followers_count' => $followers_count,
            ]);
    }
}
