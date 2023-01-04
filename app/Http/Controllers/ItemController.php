<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Post;

use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public static function get_ogps($URL)
    {
        $ogps = array();
        
        //URL先のHTML要素を持ってくる
        $html = mb_convert_encoding(file_get_contents($URL), "utf-8", "auto");
        
        //タイトルを検索
        if(preg_match('<meta property="og:title" content="(.*?)">', $html, $ogp_result))
        {
            //あるときは数字、ないときはfalseが返ってくる
            $og_title = $ogp_result[1];
        }
        else
        {
            $og_title = false;
        }
        
        //画像を検索.*は任意の文字が続くことのワイルドカードの表現
        //twitter用の画像の方が正方形だったり何かと便利そうなのでとってくる
        if(preg_match('<meta name="twitter:image" content="(.*)">', $html, $ogp_result))
        {
            //あるときは数字、ないときはfalseが返ってくる
            $og_image = $ogp_result[1];
        }
        else
        {
            //もしtwitter:imageがなかったとき
            if(preg_match('<meta property="og:image" content="(.*)">', $html, $ogp_result))
            {
                $og_image = $ogp_result[1];
            }
            else
            {
                $og_image = false;
            }
        }
        
        return $ogps = [
            'og_image' => $og_image,
            'og_title' => $og_title,
            ];
    
    return false;
    }
    
    public static function store_items($input_items, $post)
    {
        //インプット内のそれぞれのURLに対して作業
        foreach($input_items as $item){
            //値はあるかチェック
            if(!empty($item))
            {
                //URLの重複チェック。すでにある時は保存しない
                if (!Item::where('URL', $item)->exists())
                {
                    //URLからogp取得
                    $ogps = ItemController::get_ogps($item);
                    //dd($ogps['og_title']);
                    
                    //保存。fillだと一個しか登録できない
                    Item::create([
                        'URL' => $item,
                        'og_title' => $ogps['og_title'],
                        'og_image' => $ogps['og_image'],
                    ]);
                }
                
                //中間テーブルに保存
                //まずitemのidをとってくる。
                //もしも複数同じものがあった時エラーを起こさない用firstメソッド
                $item_withid = Item::where('URL', $item)->first();
                
                DB::table('item_post')->insert([
                    'item_id' => $item_withid->id,
                    'post_id' => $post->id,
                    'type' => "item",
                    ]);
            }
        }
    }
    
    public static function store_references($input_references, $post)
    {
        
        //インプット内のそれぞれのURLに対して作業
        foreach($input_references as $reference){
            //値はあるかチェック
            if(!empty($reference))
            {
                //URLの重複チェック。すでにある時は保存しない
                if (!Item::where('URL', $reference)->exists())
                {
                    //URLからogp取得
                    $ogps = ItemController::get_ogps($reference);
                    
                    //保存。fillだと一個しか登録できない
                    Item::create([
                        'URL' => $reference,
                        'og_title' => $ogps['og_title'],
                        'og_image' => $ogps['og_image'],
                    ]);
                }
        
                //中間テーブルに保存
                //まずitemのidをとってくる。
                //もしも複数同じものがあった時エラーを起こさない用firstメソッド
                $reference_withid = Item::where('URL', $reference)->first();
                
                DB::table('item_post')->insert([
                    'item_id' => $reference_withid->id,
                    'post_id' => $post->id,
                    'type' => "ref",
                    ]);
                //itemに結びついたpostのidを呼んで、入れてsyncで保存
                //attachはダブりあり、syncは同じidの結びつきは一個だけ
                //$item_withid->Posts()->attach($post->id);   
            }
        }
    }
    
    public static function update_items($input_items, $before_URLs, $post)
    {
        //$before_URLs:前までこの投稿に結びついていたURL
        //$input_items:今の更新でこの投稿に結びつけようとしているURL
        
        //保存する際には注意が必要なのでここで処理をする
        //「前は『ある』、今は『ない』」は削除する。
        //「前は『ない』、今は『ある』」はあらたに保存する。
        
        //input_URLsは値の配列、一方before_URLsは「アイテムモデル」の配列(ややこしい)
        //そのため、before_URLsを値の配列に変換する
        $before_items = array();
        
        foreach($before_URLs['items'] as $item)
        {
            $before_items[] = $item->URL;
        }
        
        /**
         * このforeachでやること：前にあったけど今ないやつを削除する。
         * 
         * array_diff(前回のURL配列 - 今回のURL配列(input) = 「前回」はあったけど今回はなくなったURL)
         */
        foreach(array_diff($before_items, $input_items) as $deleted_item)
        {
            //getだとコレクションになってしまう
            $deleted_item = Item::where('URL', $deleted_URL)->first();
            //dd($deleted_item);
            $deleted_item->Posts()->detach($post->id);
        }
        
        /**
         * このforeachでやること：前なかったやつを登録する。
         * 
         * Itemsに登録されて「ない」ときは、Itemsに登録、関係も登録(中間テーブル)。
         * Itemsに登録されて「いる」ときは、関係だけ登録。
         * 
         * array_diff(今回のURL配列(input) - 前回のURL配列 = 前回はなかったけど「今回」はあるURL)
         */
        foreach(array_diff($input_items, $before_items) as $new_item)
        {
            //空欄の値を持ってきてないか
            if($new_item !== null)
            {
                //URLはすでにItemテーブルに登録されているか？
                if(!Item::where('URL', $new_item)->exists())
                {
                    //URLからogp取得
                    $ogps = ItemController::get_ogps($new_item);
                    
                    //保存。fillだと一個しか登録できない
                    Item::create([
                        'URL' => $new_item,
                        'og_title' => $ogps['og_title'],
                        'og_image' => $ogps['og_image'],
                    ]);
                }
                
                //中間テーブルに保存
                    //まずitemのidをとってくる。
                    //もしも複数同じものがあった時エラーを起こさない用firstメソッド
                    //attachはダブりあり、syncは同じidの結びつきは一個だけ
                    $item_withid = Item::where('URL', $new_item)->first();
                    //itemに結びついたpostのidを呼んで、入れてsyncで保存
                    DB::table('item_post')->insert([
                    'item_id' => $item_withid->id,
                    'post_id' => $post->id,
                    'type' => "item",
                    ]);
            }
        }
    }
    
    public static function update_references($input_references, $before_URLs, $post)
    {
        //$before_URLs:前までこの投稿に結びついていたURL
        //$input_references:今の更新でこの投稿に結びつけようとしているURL
        
        //保存する際には注意が必要なのでここで処理をする
        //「前は『ある』、今は『ない』」は削除する。
        //「前は『ない』、今は『ある』」はあらたに保存する。
        
        //input_referencesは値の配列、一方before_URLsは「アイテムモデル」の配列(ややこしい)
        //そのため、before_URLsを値の配列に変換する
        $before_references = array();
        
        foreach($before_URLs['references'] as $reference)
        {
            $before_references[] = $reference->URL;
        }
        
        /**
         * このforeachでやること：前にあったけど今ないやつを削除する。
         * 
         * array_diff(前回のURL配列 - 今回のURL配列(input) = 「前回」はあったけど今回はなくなったURL)
         */
        foreach(array_diff($before_references, $input_references) as $deleted_reference)
        {
            //getだとコレクションになってしまう
            $deleted_reference = Item::where('URL', $deleted_URL)->first();
            //dd($deleted_reference);
            $deleted_reference->Posts()->detach($post->id);
        }
        
        /**
         * このforeachでやること：前なかったやつを登録する。
         * 
         * Itemsに登録されて「ない」ときは、Itemsに登録、関係も登録(中間テーブル)。
         * Itemsに登録されて「いる」ときは、関係だけ登録。
         * 
         * array_diff(今回のURL配列(input) - 前回のURL配列 = 前回はなかったけど「今回」はあるURL)
         */
        foreach(array_diff($input_references, $before_references) as $new_reference)
        {
            //空欄の値を持ってきてないか
            if($new_reference !== null)
            {
                //URLはすでにItemテーブルに登録されているか？
                if(!Item::where('URL', $new_reference)->exists())
                {
                    //URLからogp取得
                    $ogps = ItemController::get_ogps($new_reference);
                    
                    //保存。fillだと一個しか登録できない
                    Item::create([
                        'URL' => $new_reference,
                        'og_title' => $ogps['og_title'],
                        'og_image' => $ogps['og_image'],
                    ]);
                }
                
                //中間テーブルに保存
                    //まずitemのidをとってくる。
                    //もしも複数同じものがあった時エラーを起こさない用firstメソッド
                    //attachはダブりあり、syncは同じidの結びつきは一個だけ
                    $ref_withid = Item::where('URL', $new_reference)->first();
                    //itemに結びついたpostのidを呼んで、入れてsyncで保存
                    DB::table('item_post')->insert([
                    'item_id' => $ref_withid->id,
                    'post_id' => $post->id,
                    'type' => "ref",
                    ]);
            }
        }
    }
    
    /**
     * 該当ポストで、アイテムテーブルに保存されているURLはどのような扱いなのか確認する
     * たとえば同じURLでも、投稿Aでは「アイテム」、投稿Bでは「参考サイト」扱いしてるかも
     * それを区別するために、中間テーブルに保存したtypeから復元する作業をする
     * 
     * 返り値 : $search_URLs(['items'=>$items, 'references'=>$references])
     */
    public static function pass_converted_URLs(Post $post)
    {
        //dd($post);
        
        $items = array();
        $references = array();
        
        //URLをよぶ
        $URLs = $post->items;
        
        //dd($URLs);
        
        foreach($URLs as $URL)
        {
            //ポストに結びついたものを持ってきているわけなので、
            //中間テーブルで該当のURLで検索する
            //そうして、該当のURLはポストの中でitem/refどちらで使われているか判別
            $type_checker = DB::table('item_post')->where('item_id', $URL->id)->first();
            
            //使用アイテムだった場合
            if($type_checker->type === 'item')
            {
                $items[] = $URL;
            }
            //参考サイトだった場合
            elseif($type_checker->type === 'ref')
            {
                $references[] = $URL;
            }
        }
        
        //return用に変換
        $search_URLs = [
            'items' => $items,
            'references' => $references
            ];
        
        //dd($search_URLs);
        
        return $search_URLs;
    }
}
