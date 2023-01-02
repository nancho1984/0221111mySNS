<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('addressname', 100)->unique()->word('アカウントid');
            $table->string('nickname', 100)->comment('ニックネーム');
            $table->string('email', 500)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('image')->default('https://onl.bz/Fs6GBwq')->nullable()->comment('プロフ画像');
            $table->string('profile_sentence', 5000)->nullable()->comment('自己紹介文');
            $table->string('password', 10000);
            //人気度を測る
            $table->integer('count_follows')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
