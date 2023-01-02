<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Item;

//DBファザードとDatetime使用のための宣言２つ
use Illuminate\Support\Facades\DB;
use DateTime;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            //アイテム作成
            DB::table('items')->insert([
                ['id' => '1',
                'URL' => 'https://booth.pm/ja/items/2851701',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                ],
                ['id' => '2',
                'URL' => 'https://booth.pm/ja/items/3806580',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                ],
                ['id' => '3',
                'URL' => 'https://booth.pm/ja/items/3456526',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                ],
                ['id' => '4',
                'URL' => 'https://booth.pm/ja/items/3712777',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                ],
                ['id' => '5',
                'URL' => 'https://booth.pm/ja/items/1258744',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                ],
                
                //参考サイト作成
                ['id' => '6',
                'URL' => 'https://vrc.wiki/beginner/838/',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                ],
                ['id' => '7',
                'URL' => 'https://yukineko.me/archives/910',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                ],
                ['id' => '8',
                'URL' => 'https://www.youtube.com/watch?v=Xg4AXYuzEqA',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                ],
            ]);
        
    }
}
