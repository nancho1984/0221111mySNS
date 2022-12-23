<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
//テーブルの各カラムを引っ張ってくる
use App\Models\Post;
//DBファザードとDatetime使用のための宣言２つ
use Illuminate\Support\Facades\DB;
use DateTime;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1レコード
        //DB::table('posts')->insert([
            //'body' => 'first post',
            //'user_id' => 1,
            //'image' => 'imagetitle_1',
            //'tag1' => 'ゴスロリ',
            //'tag2' => '自作',
            //'tag3' => 'ちと',
            //'tag4' => '地雷系',
            //'tag5' => 'ピアス',
            //'created_at' => new DateTime(),
            //'updated_at' => new DateTime(),
        //]);
        
        //factory10こ 
        Post::factory()->count(10)->create();
    }
}
