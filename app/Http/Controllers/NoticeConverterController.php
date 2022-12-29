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

class NoticeConverterController extends Controller
{
    public static function notice_converter($notifications)
    {
        //通知にあってほしいもの
        //notices_id : 通知を管理するid
        //user_id : 通知先
        //type : 通知タイプ
        //agents : ユーザーのidが入った配列
        //post_id : 通知内容と結びついている投稿のid nullable
        
        $convert_notices = array();
        
        $follow_agents = array();
        $like_agents = array();
        $like_count = array();
        $liked_posts = array();
        
        //ちなみにコメントはそのまま通さなければならないので注意
        
        foreach ($notifications as $notification)
        {
            
            /*
             * -------------------------------------
             * フォローのとき
             * -------------------------------------
             */
            if($notification->type == "follow")
            {
                //該当のいいね情報をもっている
                $target_follow = Follow::where('id', $notification->type_id)->first();
                
                //次にフォローしてきた人の配列にユーザーを追加
                $the_agent = User::where('id', $target_follow->user_id);
                array_push($follow_agents, $the_agent);
                
                //フォローカウントはいくつあるか
                if(count($follow_agents) === 1)
                {
                    //まだフォローをまとめた通知が作られてないとき
                    //通知を作る
                    array_push($convert_notices, [
                    'type' => "follow",
                    'notices_id' => count($convert_notices)+1,
                    //'post_id' => NULL,
                    'agents' => $follow_agents,
                    'count' => 1,
                    ]);
                    
                    //dd($convert_notices);
                    
                }else{
                    //すでに該当idが「ある」とき
                    
                    //変更しなければいけない通知を探してきて
                    //$the_noticeにいったん代入する
                    $the_notice = $convert_notices[array_search('follow', $convert_notices)];
                    
                    //[$follow_count]フォローされた数に＋１
                    $the_notice['count'] += 1;
                    
                    //$convert_noticesにもういっかいしまう
                    $convert_notices[array_search($target_follow, $convert_notices)] = $the_notice;
                    
                    //dd($convert_notices);
                }
            }
            
            /*
             * -------------------------------------
             * いいねのとき
             * -------------------------------------
             */
            elseif($notification->type == "like")
            {
                //該当のいいね情報をもっている
                $target_like = Like::where('id', $notification->type_id)->first();
                
                //これまでの通知用にまとめた配列の中に、
                //該当のいいねと結びつく投稿のidがないか探す
                //array_searchは「ある」ときはindex番号,「ない」ときはfalseを返す。
                $like_index_number = array_search($target_like->post_id, $liked_posts);
                
                if($like_index_number === false)
                {
                    //まず「いいねされた投稿」配列にidを追加
                    array_push($liked_posts, $target_like->post_id);
                    
                    //今追加したidのインデックスの番号特定
                    $new_like_index_number = array_search($target_like->post_id, $liked_posts);
                    
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
                    
                    //通知を作る
                    array_push($convert_notices, [
                    'type' => "like",
                    'notices_id' => count($convert_notices)+1,
                    'post_id' => $target_like->post_id,
                    'agents' => $like_agents[$new_like_index_number],
                    'count' => $like_count[$new_like_index_number],
                    ]);
                    
                    //dd($convert_notices);
                    
                }else{
                    //すでに該当idが「ある」とき
                    //「ある」のでpost_idは追加しない
                    
                    //該当の投稿idのインデックスの番号は$index_number
                    //それぞれの配列の同じ番号のところに情報を追加していく
                    
                    //[$like_agents]その投稿にいいねをした人の配列
                    //その中から、まずpost_idに対応するユーザーの配列を呼び出し、
                    //配列の中に投稿と結びついているユーザーを加えていく
                    $the_agent = User::where('id', $target_like->user_id)->first();
                    array_push($like_agents[$like_index_number], $the_agent);
                    
                    //[$like_count]その投稿についた、通知対象としてのいいねの数
                    $like_count[$like_index_number] += 1;
                    
                    //変更しなければいけない通知のIDを探してきて
                    //$the_noticeにいったん代入する
                    $the_notice = $convert_notices[array_search($target_like->post_id, $convert_notices)];
                    //dd($the_notice);
                    
                    //通知を更新する
                    $the_notice['agents'] = $like_agents[$like_index_number];
                    $the_notice['count'] = $like_count[$like_index_number];
                    
                    //$convert_noticesにもういっかいしまう
                    $convert_notices[array_search($target_like->post_id, $convert_notices)] = $the_notice;
                    
                    //dd($convert_notices);
                }
            }
            
            /*
             * -------------------------------------
             * コメント・返信のとき
             * -------------------------------------
             */
            else
            {
                //該当のいいね情報をもっている
                $target_reply = Comment::where('id', $notification->type_id)->first();
                
                //次にフォローしてきた人の配列にユーザーを追加
                $the_agent = User::where('id', $target_reply->user_id)->first();
                //dd($the_agent);
                
                //投稿へのコメントか、コメントへの返信か
                if($notification->type == "replyPost")
                {
                    //投稿へのコメントのとき
                    $reply_type = "replyPost";
                    
                    //dd($convert_notices);
                    
                }else{
                    //コメントへの返信のとき
                    $reply_type = "replyCmnt";
                }
                
                //通知を作る
                    array_push($convert_notices, [
                    'type' => $reply_type,
                    'notices_id' => count($convert_notices)+1,
                    'post_id' => $target_reply->parent_post_id,
                    'agents' => $the_agent,
                    'message' => $target_reply->body,
                    'count' => 1,
                    ]);
            }
        }
        
        //もらった$notifications変数は使わないでビュー先にはこれを使わせる
        return $convert_notices;
    }
}
