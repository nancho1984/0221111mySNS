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
            'post.body' => 'max:500',
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
 
        return [
            'required' => ":attributeは必須項目です。",
            'mimes' => "指定された拡張子(PNG/JPG/GIF)ではありません。",
            'post.image.max' => "画像のファイルサイズが1MBを超えています",
            'post.body.max' => "説明文が500文字を超えています。",
            'post.tag1.max' => "タグが20文字を超えています。",
            'post.tag2.max' => "タグが20文字を超えています。",
            'post.tag3.max' => "タグが20文字を超えています。",
            'post.tag4.max' => "タグが20文字を超えています。",
            'post.tag5.max' => "タグが20文字を超えています。",
        ];
    }

}