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
        //user_idもnullableにしてるので後で再確認のほどよろしく
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('body', 500);
            $table->foreignId('user_id')->constrained()->nullable;
            $table->string('image')->nullable();
            $table->string('tag1',10)->nullable();
            $table->string('tag2',10)->nullable();
            $table->string('tag3',10)->nullable();
            $table->string('tag4',10)->nullable();
            $table->string('tag5',10)->nullable();
            $table->timestamps();
            
            //外部キー参照
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
};
