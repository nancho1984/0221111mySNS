<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;

//コメントのデリート機能を作る際は通知も消すようにしてあげてください
//likeのunlike関数など参考に

class CommentController extends Controller
{
    public function show(Comment $comment)
    {
        return view('show')->with(['comments' => $comment])->orderBy('created_at', 'DESC');
    }
    
    public function change_reply_user_id_to_name()
    {
        $replied_user = User::where('reply_user_id', $this->reply_user_id)->get();
        $user_name = $replied_user->name;
        return $user_name;
    }
    
    //Postに対する返信
    public function replyPost(Request $request, Post $post, Comment $comment)
    {
        if($request['comment']['body'] === null)
        {
            return back();
        }
        //dd($request['comment']);
        $input = $request['comment'];
        
        $comment->user_id = auth()->id();
        $comment->parent_post_id = $post-> id;
        
        //dd($request);
        //dd($input);
        $comment->fill($input)->save();
        
        //通知を送る
        NotificationController::reply_post_notice($comment);
        
        return redirect('/posts/' . $post->id);
    }
    
    //Commentに対する返信
    public function replyComment(Request $request, Post $post, Comment $comment)
    {
        $reply_comment = new Comment();
        
        $input = $request['comment'];
        
        //入力されたフォーム内容をいったんぶち込む
        $reply_comment->fill($input);
        
        //フォーム以外で必要な情報を以下でちまちま詰める
        $reply_comment->user_id = auth()->id();
        $reply_comment->parent_post_id = $post->id;
        $reply_comment->reply_user_id = $comment->user_id;
        
        //dd($reply_comment);
        
        $reply_comment->save();
        
        //通知を送る
        NotificationController::reply_comment_notice($reply_comment);
        
        return redirect('/posts/' . $post->id);
    }
}
