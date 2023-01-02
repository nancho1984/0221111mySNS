<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;use DateTime;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        //\App\Models\User::factory()->create([
        //     'name' => 'ゲスト',
        //     'email' => 'guest@guest.com',
        //]);
        
        //ユーザーを作りたいとき
        //$this->call(UserSeeder::class);
        
        //アイテムを作る
        //$this->call(ItemSeeder::class);
        
        //投稿を作りたいとき
        $this->call(PostSeeder::class);
        
        //フォローを作りたいとき 注：フォローさせるユーザーの設定をしてから
    }
}
