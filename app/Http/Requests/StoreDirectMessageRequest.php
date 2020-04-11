<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * フォームリクエスト ダイレクトメッセージ
 *
 * ここの設定を元にバリデーションを行う
 *
 * @package App\Http\Requests
 */
class StoreDirectMessageRequest extends FormRequest
{
    /**
     * 認証
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * バリデーションルール
     *
     * @return array
     */
    public function rules()
    {
        return [
            'message' => ['required', 'string', 'max:255'],
        ];
    }
}
