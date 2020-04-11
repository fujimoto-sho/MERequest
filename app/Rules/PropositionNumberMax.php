<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * バリデーションルール 案件タイプよって入力された数値の最大値を変更する
 *
 * 単発：999,999,000（千円単位）
 * レベニューシェア：100
 *
 * 最大値より大きい値が入力されていたらバリデーションエラーとする
 *
 * @property array attributes
 * @property int max 最大値
 * @package App\Rules
 */
class PropositionNumberMax implements Rule
{
    /**
     * 初期処理
     *
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;

        if ($this->attributes['type'] === '0') {
            // 単発の場合は最大 999,999千円
            $this->max = 999999;
        } else {
            // レベニューシェアの場合は最大100
            $this->max = 100;
        }
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
        return $value <= $this->max;
    }

    /**
     * バリデーションエラー時のメッセージ
     *
     * @return string メッセージ
     */
    public function message()
    {
        return $this->max . ' 以下のみ有効です';
    }
}
