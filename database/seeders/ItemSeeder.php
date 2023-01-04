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
                'og_title' => '【30アバター対応】ロリータシリーズ -セーラー-【PB対応】 - かぷちやのぶーす - BOOTH',
                'og_image' => 'https://booth.pximg.net/c/620x620/fb6b1fad-86c2-45e3-89e8-e117c662ecc2/i/2851701/bc80fbc3-6ac0-4b1c-975b-19270879e375_base_resized.jpg',
                'URL' => 'https://booth.pm/ja/items/2851701',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                ],
                ['id' => '2',
                'og_title' => '【VRCグライダー&amp;ダッシュシステム】空中を滑空＆素早い移動‼️【VRC Glide Jumping System】 - VRC合法チート研究会 - BOOTH',
                'og_image' => 'https://booth.pximg.net/c/620x620/a8057578-1c7a-4a40-a854-194c23188696/i/3806580/3daf4b0d-748f-47b8-935c-70ba83706861_base_resized.jpg',
                'URL' => 'https://booth.pm/ja/items/3806580',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                ],
                ['id' => '3',
                'og_title' => '【舞夜向け】涙袋メイク+瞳テクスチャ - Oichanchi Workshop. - BOOTH',
                'og_image' => 'https://booth.pximg.net/c/620x620/256f7c1d-3774-4c8b-a51a-f742cf2f0679/i/3456526/cb92dee4-e862-429b-b3fa-a967f7b241c6_base_resized.jpg',
                'URL' => 'https://booth.pm/ja/items/3456526',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                ],
                ['id' => '4',
                'og_title' => 'HP - 水瀬用Hair - Anka - BOOTH',
                'og_image' => 'https://booth.pximg.net/c/620x620/343455c8-13fa-4871-aa62-61ca786c23f1/i/4126531/0ab368a9-0aca-4a17-8414-7b8595f66d1c_base_resized.jpg',
                'URL' => 'https://booth.pm/ja/items/4126531',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                ],
                ['id' => '5',
                'og_title' => 'VRCAvatarEditor beta - がとーしょこらのおみせ - BOOTH',
                'og_image' => 'https://booth.pximg.net/c/620x620/5daaaeb1-43fa-4c89-8da3-e7e44093dddb/i/1258744/ce02b894-6237-4777-9d3a-853a5e1af119_base_resized.jpg',
                'URL' => 'https://booth.pm/ja/items/1258744',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                ],
                
                //参考サイト作成
                ['id' => '6',
                'og_title' => 'アバター導入方法 Step.4b : SDK3版 - VRChat初心者向けガイド',
                'og_image' => 'https://vrc.wiki/wp-content/uploads/2022/01/512x512.png',
                'URL' => 'https://vrc.wiki/beginner/838/',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                ],
                ['id' => '7',
                'og_title' => '【VRChat】Blenderを使ってアバター非対応の服に着替える',
                'og_image' => 'https://yukineko.me/wp-content/uploads/2021/11/img_61876f22a40c2.png',
                'URL' => 'https://yukineko.me/archives/910',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                ],
                ['id' => '8',
                'og_title' => null,
                'og_image' => null,
                'URL' => 'https://www.youtube.com/watch?v=Xg4AXYuzEqA',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                ],
            ]);
        
    }
}
