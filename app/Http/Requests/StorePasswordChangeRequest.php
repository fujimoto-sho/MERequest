<?php

namespace App\Http\Requests;

use App\Rules\OldPassword;
use Illuminate\Foundation\Http\FormRequest;

/**
 * フォームリクエスト パスワード変更
 *
 * ここの設定を元にバリデーションを行う
 *
 * @package App\Http\Requests
 */
class StorePasswordChangeRequest extends FormRequest
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
            'old_password'          => ['required', new OldPassword()],
            'password'              => ['required', 'min:8', 'max:255', 'confirmed'],
            'password_confirmation' => ['required'],
        ];
    }
}
