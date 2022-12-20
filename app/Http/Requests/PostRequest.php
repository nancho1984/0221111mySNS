<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        
        return [
            'post.body' => 'required|max:500',
            //mimes:jpeg,png,jpg,gif,軽い画像いける、でも重い画像むり(3GB以上くらい)、バリデーション効いてない
            //Call to a member function getRealPath() on null
            'post.image'=> 'required|max:1024|mimes:jpeg,png,jpg,gif',
            'post.tag1' => 'max:20',
            'post.tag2' => 'max:20',
            'post.tag3' => 'max:20',
            'post.tag4' => 'max:20',
            'post.tag5' => 'max:20',
        ];
    }
    
    
    public function messages()
    {
        //文字制限とサイズ制限がごっちゃになってるので調整して
        return [
            'required' => "必須項目です。",
            'mimes' => "指定された拡張子(PNG/JPG/GIF)ではありません。",
            'post.image.max' => "ファイルサイズ上限の1MBを超えています",
            'post.body.max' => "規定の文字数を超えています。",
            'post.tag1.max' => "規定の文字数を超えています。",
            'post.tag2.max' => "規定の文字数を超えています。",
            'post.tag3.max' => "規定の文字数を超えています。",
            'post.tag4.max' => "規定の文字数を超えています。",
            'post.tag5.max' => "規定の文字数を超えています。",
        ];
    }
}
