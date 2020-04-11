<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * フォームリクエスト ユーザー
 *
 * ここの設定を元にバリデーションを行う
 *
 * @package App\Http\Requests
 */
class StoreUserRequest extends FormRequest
{
    /**
     * 認証チェック
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
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->whereNot('email', Auth::user()->email)],
            'bio'   => ['nullable', 'string', 'max:255'],
            'image' => ['image', 'max:3000'],
        ];
    }

    /**
     * エラーメッセージ
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.unique' => 'このメールアドレスは使用できません',
        ];
    }
}
