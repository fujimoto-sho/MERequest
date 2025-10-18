<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

/**
 * バリデーションルール 退会ユーザー判定
 *
 * 退会していたらバリデーションエラーとする
 *
 * @package App\Rules
 */
class NotExistsWithdrawUsers implements ValidationRule
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
        // 退会しているかの判定
        // withdraw_usersテーブルにメールアドレスが存在したら退会済とする
        if (DB::table('withdraw_users')->where('email', $value)->exists()) {
            $fail('認証に失敗しました');
        }
    }
}
