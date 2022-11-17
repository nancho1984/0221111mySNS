<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    //以下参考サイトから持ってきたもの。まだわからん適宜修正する
    
    protected Item $item;

    /**
     * ItemController constructor.
     * @param Item $item
     */
    public function __construct(Item $item)
    {
        $this->item = $item;
    }

    /**
     * 商品登録
     */
    public function store(Request $request)
    {
        $item = $this->item->create([
            'name' => 'ウィスキーボンボン'
        ]);
        $item->categories()->sync([1, 2]);
    }

    /**
     * 商品取得
     */
    public function index()
    {
        $items = $this->item->all();

        foreach ($items as $item) {
            echo "<br/>{$item->name}のカテゴリたち：";
            foreach ($item->categories as $category) {
                echo "{$category->name} ";
            }
        }
    }
}
