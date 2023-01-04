<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//ここではnotification,post以外のテーブルを作成する

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //コメントテーブル--------
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('body', 200);
            $table->unsignedBigInteger('parent_post_id')->nullable();
            $table->unsignedBigInteger('reply_user_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
            
            //検索用インデックス
            $table->index('id');
            $table->index('user_id');
            $table->index('parent_post_id');
            $table->index('reply_user_id');
            
            //外部キー参照
            $table->foreign('parent_post_id')
                ->references('id')
                ->on('posts')
                ->onUpdate('CASCADE');
                
            $table->foreign('reply_user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('CASCADE');
        });
     
     
        //アイテムテーブル--------
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('URL', 500)->unique();
            //OGPの情報を補完するカラム
            $table->string('og_image', 500)->nullable();
            $table->string('og_title' ,500)->nullable();
            //タイムスタンプをつけようとした。消してもいいっちゃいい
            $table->timestamps();
            $table->softDeletes();
            
            //検索用インデックス
            $table->index('id');
            $table->index('URL');
            $table->index('og_image');
            $table->index('og_title');
        });
     
    
        //フォローテーブル--------
        Schema::create('follows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('following_user_id');
            $table->unsignedBigInteger('followed_user_id');
            $table->softDeletes();
            $table->timestamps();
            
            //検索用インデックス
            $table->index('id');
            $table->index('following_user_id');
            $table->index('followed_user_id');
            
            //外部キー参照
            $table->foreign('following_user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('CASCADE');
            $table->foreign('followed_user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('CASCADE');
        });
        
     
        //いいねテーブル--------
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('post_id')->constrained();
            $table->softDeletes();
            $table->timestamps();
            
            //検索用インデックス
            $table->index('id');
            $table->index('user_id');
            $table->index('post_id');
        });
        
     
        //アイテムと投稿の中間テーブル--------
        Schema::create('item_post', function (Blueprint $table) {
            $table->foreignId('item_id')->constrained()->nullable;
            $table->foreignId('post_id')->constrained()->nullable;
            //type: URLがitemなのかref(Reference)なのか判別
            //値はitem or ref
            $table->string('type', 10)->nullable();
            $table->softDeletes();
            $table->timestamps();
            
            //主キー設定
            $table->primary(['item_id', 'post_id']);
            
            //検索用インデックス
            $table->index('item_id');
            $table->index('post_id');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
        Schema::dropIfExists('items');
        Schema::dropIfExists('follows');
        Schema::dropIfExists('likes');
        Schema::dropIfExists('item_post');
    }
};
