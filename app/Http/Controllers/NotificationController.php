<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\Post;
use App\Models\User;
use App\Models\Follow;
use App\Models\Like;
use App\Models\Comment;

use Carbon\Carbon;
use App\Http\Controllers\NoticeConverterController;

class NotificationController extends Controller
{
    /**
     * showNotices：通知一覧を表示する関数
     * 
     * 使い道 : 通知一覧画面を表示したいとき
     */
    public static function showNotices(User $user, Notification $notification)
    {
        //dd($notifications->get());
        //ユーザー宛の通知をテーブルから検索する
        $notifications = $notification->where('user_id', $user->id)
            ->where('read_at', NULL)->get();
        
        //dd($notifications);
        
        //未読の通知が「ある」ときにifの中身を行う
        if($notifications != NULL)
        {
            //表示させる予定の通知たちに「既読」をつけてあげる
            foreach($notifications as $notification)
            {
                //nowで今の時間をとってきて、toDateTimeStringでテーブルにあわせた形に
                $notification->read_at = Carbon::now('Asia/Tokyo')->toDateTimeString();
                $notification->save();
                //dd($notification);
            }
        }
        
        $convert_notices = NoticeConverterController::notice_converter($notifications);
        //dd($convert_notices);
        //dd('YES');
        
        return view('show_notices',compact(
            'convert_notices',
            ));
    }
    
    //追加：フォロー通知
    public static function follow_notice(Follow $follow)
    {
        //dd($follow);
        $agent = User::where('id', $follow->following_user_id)->first();
        
        //notificationテーブルに入れるための型を一対一で作っていく
        $notification =
            Notification::create([
                'user_id'=>$follow->followed_user_id,
                'type' => "follow",
                'type_id' => $follow->id,
                'agent_id' => $agent->id,
            ]);
    }
    
    //削除：フォロー通知
    //相手が通知を「未読」の場合のみ削除する
    public static function delete_follow_notice(Follow $follow)
    {
        //dd($follow);
        
        //消したい通知をnotificationテーブルから探す
        //user_id,type,type_idで特定する
        $delete_notice = 
            Notification::where('user_id', $follow->followed_user_id)
                ->where('type', "follow")
                ->where('type_id', $follow->id)->first();
        
        //この通知は未読のものか、チェックする
        //すでに通知が読まれてるなら、消したとて時すでにお寿司だと考えられるため
        if($delete_notice->read_at == NULL)
        {
            //dd("yet_read");
            //dd($delete_notice);
            
            $delete_notice->delete();
        }
    }
    
    
    
    //追加：いいね通知
    public static function like_notice(Like $like)
    {
        //dd($like);
        
        //$agent : いいねをした人
        //モデル・インスタンスからしかnicknameが取れないのでfirst()使用
        $agent = User::where('id', $like->user_id)->first();
        //dd($agent);
        
        //likeは「投稿」と「いいねした人」しか情報を持ってないので
        //「投稿をいいねされた人」の情報をなんとか持ってくる
        $post_liked = Post::where('id', $like->post_id)->first();
        
        //notificationテーブルに入れるための型を一対一で作っていく
        $notification =
            Notification::create([
                'user_id'=>$post_liked->user_id,
                'type' => "like",
                'type_id' => $like->id,
                'agent_id' => $agent->id,
            ]);
        //dd($notification);
    }
    
    //削除：いいね通知
    //相手が通知を「未読」の場合のみ削除する
    public static function delete_like_notice(Like $like)
    {
        //dd($like);
        
        //likeは「投稿」と「いいねした人」しか情報を持ってないので
        //「投稿をいいねされた人」の情報をなんとか持ってくる
        $post_liked = Post::where('id', $like->post_id)->first();
        
        //消したい通知をnotificationテーブルから探す
        //user_id,type,type_idで特定する
        $delete_notice = 
            Notification::where('user_id', $post_liked->user_id)
                ->where('type', "like")
                ->where('type_id', $like->id)->first();
        
        //この通知は未読のものか、チェックする
        //すでに通知が読まれてるなら、消したとて時すでにお寿司だと考えられるため
        if($delete_notice->read_at == NULL)
        {
            //dd("yet_read");
            //dd($delete_notice);
            
            $delete_notice->delete();
        }
    }
    
    
    
    //追加：「投稿」へのコメント通知
    public static function reply_post_notice(Comment $comment)
    {
        //$agent : コメントをした人
        //$comment->user_id : コメントをした人のid
        //モデル・インスタンスからしかnicknameが取れないのでfirst()使用
        $agent = User::where('id', $comment->user_id)->first();
        
        //$comment->parent_post_id : コメントした投稿のid
        //コメントのときは「投稿」と「コメントした人」しか情報を持ってないので
        //「投稿を書いた人」の情報をなんとか持ってくるために、
        //コメントされた投稿を持ってくる（投稿内に投稿主idあり）
        $post_replied = Post::where('id', $comment->parent_post_id)->first();
        
        //notificationテーブルに入れるための型を一対一で作っていく
        $notification =
            Notification::create([
                'user_id'=>$post_replied->user_id,
                'type' => "replyPost",
                'type_id' => $comment->id,
                'message' => "{$agent->nickname}さんがあなたの投稿にコメントしました",
                'agent_id' => $agent->id,
            ]);
        //dd($notification);
    }
    
    
    
    //追加：「コメント」への返信通知
    public static function reply_comment_notice(Comment $reply_comment)
    {
        //dd($reply_comment);
        
        //$agent : コメントをした人
        //$comment->user_id : コメントをした人のid
        //モデル・インスタンスからしかnicknameが取れないのでfirst()使用
        $agent = User::where('id', $reply_comment->user_id)->first();
        
        //dd($reply_comment->reply_user_id);
        
        //notificationテーブルに入れるための型を一対一で作っていく
        //$comment->reply_user_id : リプライされたユーザーのid
        $notification =
            Notification::create([
                'user_id'=>$reply_comment->reply_user_id,
                'type' => "replyCmnt",
                'type_id' => $reply_comment->id,
                'message' => "{$agent->nickname}さんがあなたのコメントに返信しました",
                'agent_id' => $agent->id,
            ]);
        //dd($notification);
    }
    
    //削除：「コメント」への返信通知
    //相手が通知を「未読」の場合のみ削除する
    public static function delete_reply_comment_notice(Comment $reply_comment)
    {
        //dd($reply_comment);
        
        //消したい通知をnotificationテーブルから探す
        //user_id,type,type_idで特定する
        $delete_notice = 
            Notification::where('user_id', $reply_comment->reply_user_id)
                ->where('type', "replyCmnt")
                ->where('type_id', $reply_comment->id)->first();
        
        //この通知は未読のものか、チェックする
        //すでに通知が読まれてるなら、消したとて時すでにお寿司だと考えられるため
        if($delete_notice->read_at == NULL)
        {
            //dd("yet_read");
            //dd($delete_notice);
            
            $delete_notice->delete();
        }
    }
}
