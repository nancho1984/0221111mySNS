<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;

class CommentController extends Controller
{
    public function show(Comment $comment)
    {
        return view('show')->with(['comments' => $comment])->orderBy('created_at', 'DESC');
    }
    
    //Postに対する返信
    public function replyPost(Request $request, Post $post, Comment $comment)
    {
        $input = $request['comment'];
        
        $comment->user_id = auth()->id();
        $comment->parent_post_id = $post-> id;
        
        //dd($request);
        //dd($input);
        $comment->fill($input)->save();
        return redirect('/posts/' . $post->id);
    }
    
    //Commentに対する返信
    public function replyComment(Request $request, Post $post, Comment $comment)
    {
        $reply_comment = new Comment();
        
        $input = $request['comment'];
        
        $reply_comment->user_id = auth()->id();
        $reply_comment->parent_post_id = $post->id;
        $reply_comment->reply_user_id = $comment->user_id;
        
        //dd($request);
        //dd($input);
        $reply_comment->fill($input)->save();
        return redirect('/posts/' . $post->id);
    }
}
