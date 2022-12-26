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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            //通知を受ける人のユーザーID
            $table->unsignedBigInteger('user_id');
            $table->string('type');
            $table->bigInteger('type_id');
            //agent_id : コメントやいいねをしてくれたユーザーのID
            $table->unsignedBigInteger('agent_id');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            //検索用インデックス
            $table->index('id');
            $table->index('user_id');
            $table->index('type');
            $table->index('type_id');
            $table->index('agent_id');
            $table->index('read_at');
            
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('CASCADE');
                
            $table->foreign('agent_id')
                ->references('id')
                ->on('users')
                ->onUpdate('CASCADE');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};
