<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * バリデーションルール 入力されたパスワードと現在のパスワードが一致しているか
 *
 * 一致していなかったらバリデーションエラーとする
 *
 * @package App\Rules
 */
class OldPassword implements Rule
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
     * バリデーションOK：true
     * バリデーションNG：false
     *
     * @param string $attribute
     * @param mixed $value 入力された値
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // ハッシュ値のチェック
        return Hash::check($value, Auth::user()->getAuthPassword());
    }

    /**
     * バリデーションエラー時のメッセージ
     *
     * @return string メッセージ
     */
    public function message()
    {
        return '現在のパスワードと一致しません';
    }
}
