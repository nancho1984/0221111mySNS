<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * ----------------------------------
 * 認証してないとアクセスできないやつ
 * 主に投稿系
 * ----------------------------------
 */
Route::group(['middleware' => ['auth']], function(){
    
    //ポスト
    //フォローしているユーザーの新着投稿が一覧で見れるところ
    Route::get('/posts/follows', [PostController::class, 'showFollowsPosts'])->name('show_follows_posts');
    Route::post('/posts', [PostController::class, 'store']);
    Route::get('/posts/create', [PostController::class, 'create']);
    Route::get('/posts/{post}/edit', [PostController::class, 'edit']);
    Route::put('/posts/{post}/update', [PostController::class, 'update']);
    Route::delete('/posts/{post}', [PostController::class,'delete']);
    
    //リプライ
    Route::post('/posts/{post}/reply/comment/{comment}', [CommentController::class, 'replyComment'])->name('replyComment');
    Route::post('/posts/{post}/reply/post', [CommentController::class, 'replyPost'])->name('replyPost');

    //いいね
    Route::get('/posts/like/{post}', [LikeController::class, 'like'])->name('like');
    Route::get('/posts/unlike/{post}', [LikeController::class, 'unlike'])->name('unlike');
    
    //ユーザー
    Route::get('/users/follow/{user}', [UserController::class, 'follow'])->name('follow');
    Route::get('/users/unfollow/{user}', [UserController::class, 'unfollow'])->name('unfollow');
    Route::get('/users/{user}/edit', [UserController::class, 'editProfile'])->name('edit_profile');
    Route::put('/users/{user}/update', [UserController::class, 'update']);
    
    //[通知]
    //通知の詳細をみせるとき
    Route::get('/notifications/{user}', [NotificationController::class, 'showNotices'])->name('show_notices');
    //通知をナビゲーションバーにわたす
    Route::get('/notifications/{user}/get', [NotificationController::class, 'getNotices'])->name('get_notices');

});


/**
 * --------------------------
 * いつでもアクセスできるやつ
 * --------------------------
 */
 
//[ポスト]
//トップ画面
Route::get('/', [PostController::class, 'showTop'])->name('top');
//新着投稿が一覧で見れるところ
Route::get('/posts/new', [PostController::class, 'showNewPosts'])->name('show_new_posts');
//ユーザーがいいねした投稿が見れる画面
Route::get('/posts/likes/user={user}', [PostController::class, 'showPostsLiked'])->name('postsliked');
//特定のユーザーの投稿が見れる画面
Route::get('/posts/user={user}', [PostController::class, 'showUsersPosts'])->name('users_posts');
//投稿全部見る画面
Route::get('/posts/{post}', [PostController::class, 'showPost'])->name('show_post');
//「ポスト」テーブルの中身で検索する
Route::get('/search/index/posts', [PostController::class, 'searchbarPosts'])->name('searchbar_posts');
//月間で人気の投稿を見る画面
Route::get('/posts/popular/month', [PostController::class, 'showMonthPopularPosts'])->name('show_month_popular_posts');
//すべての期間で人気の投稿を見る画面
Route::get('/posts/popular/entire', [PostController::class, 'showEntirePopularPosts'])->name('show_entire_popular_posts');

//[アイテム]
//投稿に結びついたURLから、他の同一のURLが結びついている投稿を見る
Route::get('/search/items/{item}', [PostController::class, 'ItemSearch']);

//[フォロー]
Route::get('/users/follows/{user}', [FollowController::class, 'show_followusers'])->name('show_followusers');
Route::get('/users/followers/{user}', [FollowController::class, 'show_followers'])->name('show_followers');

//[ユーザー]
//ユーザーのデータを登録するところ（見えない）
Route::post('/users', [UserController::class, 'store'])->name('store_user');
//ユーザープロフィールが_見れるところ
Route::get('/users/{user}', [UserController::class, 'showProfile'])->name('show_Profile');
//「ユーザー」テーブルの中身で検索する
Route::get('/search/index/users', [UserController::class, 'searchbarUsers'])->name('searchbar_users');


//上のバー
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');



//Route::get('/dashboard', function () {
//    return view('dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';

