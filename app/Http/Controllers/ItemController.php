<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Post;

class ItemController extends Controller
{
    public function __construct(Item $item)
    {
        $this->item = $item;
    }
    
    public function store(Request $request)
    {
        
        return back();
    }
    
}
