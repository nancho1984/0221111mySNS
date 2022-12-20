<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UploadSizeCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        //$_SERVER['CONTENT_LENGTH']はPHP標準のリクエストデータ量を取得するやつ
        //issetは存在確認
        if(isset($_SERVER['CONTENT_LENGTH'])){
            
            $post_max_size = $this->return_bytes(ini_get('post_max_size'));
            $uploaded_size = intval($_SERVER['CONTENT_LENGTH']);
            
            if($post_max_size < $uploaded_size){
                return back()->with('imagesize_error', 'ファイルサイズが大きすぎます。');
            }
        }
        
        return $next($request);
    }
    
    public function return_bytes($val) {
        
        $val = trim($val);        
        $unit = $val[strlen($val)-1];
        $_val = substr($val, 0, strlen($val)-1);        
        
        if($unit == 'G') 
        return intval($_val) * 1024 * 1024 * 1024;
        
        if($unit == 'M') 
        return intval($_val) * 1024 * 1024;
        
        if($unit == 'K') 
        return intval($_val) * 1024;
    }
}
