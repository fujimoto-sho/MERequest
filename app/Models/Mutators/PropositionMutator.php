<?php

namespace App\Models\Mutators;

/**
 * propositionsテーブルのミューテタ
 *
 * 値を代入する際のフォーマットを定義する
 *
 * @package App\Models\Mutators
 */
trait PropositionMutator
{
    /**
     * ミューテタ 金額/%（最小）
     *
     * 単発の場合は千円単位で入力しているため、保存する値を1000倍する
     *
     * @param $value
     */
    public function setNumberMinAttribute($value)
    {
        // 単発：1000倍、レベニューシェア：1倍（変更なし）
        $rate = $this->attributes['type'] === '0' ? 1000 : 1;
        $this->attributes['number_min'] = $value * $rate;
    }

    /**
     * ミューテタ 金額/%（最大）
     *
     * 単発の場合は千円単位で入力しているため、保存する値を1000倍する
     *
     * @param $value
     */
    public function setNumberMaxAttribute($value)
    {
        // 単発：1000倍、レベニューシェア：1倍（変更なし）
        $rate = $this->attributes['type'] === '0' ? 1000 : 1;
        $this->attributes['number_max'] = $value * $rate;
    }
}
