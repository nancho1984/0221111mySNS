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

class NotificationController extends Controller
{
    /**
     * notice_manager：渡す前の通知を管理する関数
     * 同じ投稿に対していいねがつけられたときに数をまとめたりする
     * 
     * 使い道 : 整理された通知を渡したいとき
     */
    public static function notice_manager($notifications)
    {
        //通知にあってほしいもの
        //user_id : 通知先
        //type : 通知タイプ
        //agents : ユーザーのidが入った配列
        //post_id : 通知内容と結びついている投稿のid nullable
        
        $convert_notices = array();
        $queue_notices = array();
        $queue_number = 0;
        
        $follow_agents = array();
        $follow_count = array();
        $like_agents = array();
        $like_count = array();
        $liked_posts = array();
        
        //ちなみにコメントはそのまま通さなければならないので注意
        
        foreach ($notifications as $i => $notification)
        {
            //これが何番目の通知の処理か
            
            //フォローの時
            if($notification->type == "follow")
            
            //いいねの時
            if($notification->type == "like")
            {
                //該当のいいね情報をもっている
                $target_like = Like::where('post_id', $notification->type_id)->first();
                //これまでの通知用にまとめた配列の中に、
                //該当のいいねと結びつく投稿のidがないか探す
                //array_searchは「ある」ときはindex番号,「ない」ときはfalseを返す。
                $like_index_number = array_search($target_like->post_id, $liked_posts);
                
                if($like_index_number == false)
                {
                    //まだ該当idが「ない」とき
                    
                    //まず「いいねされた投稿」配列にidを追加
                    array_push($liked_posts, $target_like->post_id);
                    
                    //今追加したidのインデックスの番号特定
                    $new_like_index_number = array_search($post_liked_id, $liked_posts);
                    
                    //それぞれの配列の同じ番号のところに情報を追加していく
                    
                    //[$like_agents]その投稿にいいねをした人の配列
                    //post_agent_arrayはlike_agents配列に入れるための配列
                    //この中に投稿と結びついているユーザー情報を加えていく
                    //ビューでアイコンとか出すようにUserモデル渡す。
                    $post_agent_array = array();
                    $the_agent = User::where('id', $target_like->user_id)->first();
                    array_push($post_agent_array, $the_agent);
                    $like_agents[$new_like_index_number] = $post_agent_array;
                    
                    //[$like_count]その投稿についた、通知対象としてのいいねの数
                    $like_count[$new_like_index_number] = 1;
                    
                }else{
                    //すでに該当idが「ある」とき
                    //「ある」のでpost_idは追加しない
                    
                    //該当の投稿idのインデックスの番号は$index_number
                    //それぞれの配列の同じ番号のところに情報を追加していく
                    
                    //[$like_agents]その投稿にいいねをした人の配列
                    //post_agent_arrayはlike_agents配列に入れるための配列
                    //まず対応するユーザーの配列を呼び出し、
                    //その中に投稿と結びついているユーザーidを加えていく
                    $post_agent_array = $like_agent[$like_index_number];
                    array_push($post_agent_array, $target_like->user_id);
                    
                    //[$like_count]その投稿についた、通知対象としてのいいねの数
                    $like_count[$like_index_number] += 1;
                }
                
            }
        }
        
        //$notificationのforeach後
        //これまでの配列情報を変換して$convert_noticesに詰めていく
        
        //いいねにまつわる通知があるとき
        if(!(empty($liked_posts)))
        {
            foreach($liked_posts as $liked_post)
            {
                //配列のインデックス番号ゲット
                $like_index_number = array_search($liked_post, $liked_posts);
                
                //$convert_noticesに配列としてつめる
                array_push($convert_notices, [
                    'type' => "like",
                    'post_id' => $liked_post,
                    'agents' => $like_agents[$like_index_number],
                    'message' => "{$like_count}人にいいね！されました",
                    ]);
            }
        }
        
        //もらった$notifications変数は使わないでビュー先にはこれを使わせる
        return $convert_notices;
    }
    
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
        
        
        $convert_notices = self::notice_manager($notifications);
        dd($convert_notices);
        //dd('YES');
        
        return view('timeline',compact(
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
