<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
    
    public function authorize()
    {
        return false;
    }
    */
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'image'=> 'max:1100|mimes:jpeg,png,jpg,gif',
            
            /**
             * uniqueは
             * "1.ユニークチェックしたいテーブル", "2.チェックしたいカラム名"
             * "3.チェック対象外にしたいレコードの主キー", "4.対象外にしたいレコードの主キーカラム名"
             */
            'addressname' => 'required|max:100|unique:users,addressname, '.$request->id.', id',
            'nickname' => 'required|max:100',
            'profile_sentence' => 'max:5000',
        ];
    }
    
    public function messages()
    {
        //attributeとかはlang/ja/validation.php参照。下の方にuser周りのやつ書いてます
        return [
            'required' => ":attributeは必須項目です。",
            'mimes' => "指定された拡張子(PNG/JPG/GIF)ではありません。",
            'image.max' => "画像のファイルサイズが1MBを超えています。",
            'addressname.unique' => "このユーザーIDはすでに使用されています。"
        ];
    }
}
