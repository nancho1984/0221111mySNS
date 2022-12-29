<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
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

    public function editProfile(User $user)
    {
        return view('edit_profile')->with(['user' => $user]);
    }
    
    public function update(Request $request, User $user)
    {
        
        //dd($request->all());
        //dd($request['profile.image']);
        
        //ユニークをうまくProfileRequestの中で書けないので
        //コントローラーでバリデーションしてる
        $request->validate([
            
            'profile.image'=> 'max:1100|mimes:jpeg,png,jpg,gif',
            
            /**
             * uniqueは
             * "1.ユニークチェックしたいテーブル", "2.チェックしたいカラム名"
             * "3.チェック対象外にしたいレコードの主キー", "4.対象外にしたいレコードの主キーカラム名"
             */
            'profile.addressname' => 'required|max:100|unique:users,addressname, '.$user->id.',id',
            'profile.nickname' => 'required|max:100',
            'profile.profile_sentence' => 'max:5000',
        ],
        [
            'required' => ":attributeは必須項目です。",
            'mimes' => "指定された拡張子(PNG/JPG/GIF)ではありません。",
            'profile.image.max' => "画像のファイルサイズが1MBを超えています。",
            'profile.addressname.unique' => "このユーザーIDはすでに使用されています。"
        ]);
        
        //request[]でDBから「型(キー)」をもらってinputをつめる
        //[user]配列はlaravelくん(多分Breeze)がすでに使っているので[profile]
        $input = $request['profile'];
        //dd($request['profile']);
        
        $user->fill($input);
        
        //dd($request->file('profile.image'));
        //画像が入っている＝新しい画像に変更されたときのみ保存
        if($request['profile.image'])
        {
            $user->image = Cloudinary::upload($request->file('profile.image')->getRealPath())->getSecurePath();
        }
        
        //ポストを保存
        $user->save();
        
        
        return redirect('/users/' . $user->id);
    }
    
    /**
     * follower：フォロー「する」
     * followee：フォロー「される」
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
        
            $follow->save();
            //フォローした通知を出すための呼び出し
            NotificationController::follow_notice($follow);
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
        
        //通知を消す用(既読済みなら消さない)
        NotificationController::delete_follow_notice($follow);
        
        $follow->delete();
        
        return back();
    }
    
    /**
     * 投稿を検索ボックスで検索するやつ
     */
    public function searchbarUsers(Request $request)
    {
        if(!($request->filled('search_users')))
        {
            return back();
        }
        
        $users = User::paginate(30);
        $search = $request->input('search_users');
        
        //検索フォームに値がある(入力されてる)かどうか
        if($search)
        {
            //全角スペースを半角スペースに変換
            $searchPhrase = mb_convert_kana($search, 's');
            
            //単語を半角スペースずつで配列化する
            //"三島 美輪明宏 肩パッド"を["三島", "美輪明宏", "肩パッド"]に
            $searchWords = preg_split('/[\s,]+/', $searchPhrase, -1, PREG_SPLIT_NO_EMPTY);
            
             //カラ配列作る
            $hit_ids=array();
            
            //dd($searchWords);
            //単語ずつで検索
            foreach($searchWords as $key=>$word)
            {
                
                //if($key===1) dd($query->get());
                
                //LIKE検索、本文、タグ
                $result=User::where('addressname', 'LIKE', '%'.$word.'%')
                    ->orwhere('nickname', 'LIKE', '%'.$word.'%')
                    ->orwhere('profile_sentence', 'LIKE', '%'.$word.'%')
                    ->pluck('id')->toArray();
                
                //見つかったidの配列を、unpackして$hit_idに格納
                array_push($hit_ids, ...$result);
            }
            
            //$hit_idsで見つけたidを全検索する。
            $users= User::whereIn('id', $hit_ids)->paginate(30);
        }
        
        //dd($searchPhrase);
        return view('search_users',compact('searchPhrase', 'users'));
    }
    
}
