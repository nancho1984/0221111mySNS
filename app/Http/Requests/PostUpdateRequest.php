<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'postupdate.body' => 'max:500',
            'postupdate.image'=> 'max:1024|mimes:jpeg,png,jpg,gif',
            'postupdate.tag1' => 'max:20',
            'postupdate.tag2' => 'max:20',
            'postupdate.tag3' => 'max:20',
            'postupdate.tag4' => 'max:20',
            'postupdate.tag5' => 'max:20',
        ];
    }
    
    public function messages()
    {
 
        return [
            'postupdate.image.mimes' => "指定された拡張子(PNG/JPG/GIF)ではありません。",
            'postupdate.image.max' => "画像のファイルサイズが1MBを超えています",
            'postupdate.body.max' => "説明文が500文字を超えています。",
            'postupdate.tag1.max' => "タグが20文字を超えています。",
            'postupdate.tag2.max' => "タグが20文字を超えています。",
            'postupdate.tag3.max' => "タグが20文字を超えています。",
            'postupdate.tag4.max' => "タグが20文字を超えています。",
            'postupdate.tag5.max' => "タグが20文字を超えています。",
        ];
    }
}
