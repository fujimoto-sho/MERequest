<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

/**
 * バリデーションルール 退会ユーザー判定
 *
 * 退会していたらバリデーションエラーとする
 *
 * @package App\Rules
 */
class NotExistsWithdrawUsers implements Rule
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
        // 退会しているかの判定
        // withdraw_usersテーブルにメールアドレスが存在したら退会済とする
        return  DB::table('withdraw_users')->where('email', $value)->doesntExist();
    }

    /**
     * バリデーションエラー時のメッセージ
     *
     * @return string メッセージ
     */
    public function message()
    {
        return '認証に失敗しました';
    }
}
