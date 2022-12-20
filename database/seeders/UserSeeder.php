<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
//テーブルの各カラムを引っ張ってくる
use App\Models\User;
//DBファザードとDatetime使用のための宣言２つ
use Illuminate\Support\Facades\DB;
use DateTime;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //ゲストユーザーを初期設定する
        DB::table('users')->insert([
            'id' => 1,
            'name' => 'ゲスト',
            'email' => 'guest@guest.com',
            'password' => 'guest',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        
    }
}
