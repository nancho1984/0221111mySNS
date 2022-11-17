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
        //コメントテーブル
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->nullable;
            $table->string('body', 200);
            $table->timestamps();
            //ポストidを参照し、参照先がnullになればnullを設定し、自身はnullable
            $table->unsignedBigInteger('parent_post_id')->references('id')->on('posts')
                ->onUpdate('CASCADE')->onDelete('SET NULL')
                ->nullable;
            //コメントidで自分自身を参照させ、参照先がnullになればnull設定、自身はnullable
            $table->unsignedBigInteger('parent_comment_id')->references('id')->on('comments')
                ->onUpdate('CASCADE')->onDelete('SET NULL')
                ->nullable;
            $table->unsignedBigInteger('child_comment_id')->references('id')->on('comments')
                ->onUpdate('CASCADE')->onDelete('SET NULL')
                ->nullable;
        });
     
        //アイテムテーブル
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('URL', 1000);
            $table->timestamps();
        });
     
        //フォローテーブル
        Schema::create('follows', function (Blueprint $table) {
            //user_idを参照し、参照先がnullになればnullを設定し、自身はnullable
            $table->unsignedBigInteger('Follower_id')->references('id')->on('users')
                ->onUpdate('CASCADE')->onDelete('SET NULL')
                ->nullable;
            $table->unsignedBigInteger('Followee_id')->references('id')->on('users')
                ->onUpdate('CASCADE')->onDelete('SET NULL')
                ->nullable;
            $table->timestamps();
            //主キー設定
            $table->primary(['Follower_id', 'Followee_id']);
        });
     
        //いいねテーブル
        Schema::create('likes', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->nullable;
            $table->foreignId('post_id')->constrained()->nullable;
            $table->timestamps();
            //主キー設定
            $table->primary(['user_id', 'post_id']);
        });
     
        //アイテムと投稿の中間テーブル
        Schema::create('item_post', function (Blueprint $table) {
            $table->foreignId('item_id')->constrained()->nullable;
            $table->foreignId('post_id')->constrained()->nullable;
            $table->timestamps();
            //主キー設定
            $table->primary(['item_id', 'post_id']);
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
