<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * バリデーションルール 入力されたパスワードと現在のパスワードが一致しているか
 *
 * 一致していなかったらバリデーションエラーとする
 *
 * @package App\Rules
 */
class OldPassword implements ValidationRule
{
    /**
     * 初期処理
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * 検証ルール
     *
     * @param string $attribute
     * @param mixed $value 入力された値
     * @param \Closure $fail
     * @return void
     */
    public function validate(string $attribute, $value, \Closure $fail): void
    {
        // ハッシュ値のチェック
        if (!Hash::check($value, Auth::user()->getAuthPassword())) {
            $fail('現在のパスワードと一致しません');
        }
    }

}
