<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\CommentController;

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
 * 認証してないとアクセスできないやつ
 * 主に投稿系
 */
Route::group(['middleware' => ['auth']], function(){
    
    //ポスト
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
    Route::get('/users/{user}/notice/get', [UserController::class, 'getNotice'])->name('get_notice');
    Route::put('/users/{user}/notice/checked', [UserController::class, 'checkedNotice'])->name('checked_notice');

});


/**
 * いつでもアクセスできるやつ
 */
//ポスト
Route::get('/', [PostController::class, 'showTop'])->name('top');
Route::get('/posts/likes/user={user}', [PostController::class, 'showPostsLiked'])->name('postsliked');
Route::get('/posts/user={user}', [PostController::class, 'showUsersPosts'])->name('users_posts');
Route::get('/posts/{post}', [PostController::class, 'showPost']);

//アイテム
Route::get('/search/items/{item}', [PostController::class, 'ItemSearch']);

//フォロー
Route::get('/users/follows/{user}', [FollowController::class, 'show_followusers'])->name('show_followusers');
Route::get('/users/followers/{user}', [FollowController::class, 'show_followers'])->name('show_followers');

//ユーザー
Route::post('/users', [UserController::class, 'store'])->name('store_user');
Route::get('/users/{user}', [UserController::class, 'showProfile'])->name('show_Profile');

//上のバー
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';

