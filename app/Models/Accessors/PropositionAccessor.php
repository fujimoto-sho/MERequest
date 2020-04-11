<?php

namespace App\Models\Accessors;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * propositionsテーブルのアクセサ
 *
 * 取得するカラムのフォーマットを定義する
 *
 * @package App\Models\Accessors
 */
trait PropositionAccessor
{
    /**
     * アクセサ 案件タイプ名
     *
     * @return string
     */
    public function getTypeNameAttribute()
    {
        return config('proposition.type_name')[$this->attributes['type']];
    }

    /**
     * アクセサ ステータス名
     *
     * @return string
     */
    public function getStatusNameAttribute()
    {
        return config('proposition.status_name')[$this->attributes['status']];
    }

    /**
     * アクセサ フォーマット済の 金額/%（最小）
     *
     * 単発：「¥」
     * レベニューシェア：なし
     *
     * @return string
     */
    public function getFormattedNumberMinAttribute()
    {
        return $this->attributes['type'] === 0 ?
            '¥'. number_format($this->attributes['number_min']) :
            $this->attributes['number_min'];
    }

    /**
     * アクセサ フォーマット済の 金額/%（最大）
     *
     * 単発：「¥」
     * レベニューシェア：「%」
     *
     * @return string
     */
    public function getFormattedNumberMaxAttribute()
    {
        return $this->attributes['type'] === 0 ?
            '¥' . number_format($this->attributes['number_max']) :
            $this->attributes['number_max'] . '%';
    }

    /**
     * アクセサ 掲載日
     *
     * 9999年99月99日形式にフォーマットする
     *
     * @return string
     */
    public function getPublicationDateAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->format('Y年m月d日');
    }

    /**
     * アクセサ お気に入り数
     *
     * @return int
     */
    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }

    /**
     * アクセサ 自分がお気に入り登録しているか
     *
     * @return string
     */
    public function getIsLikeAttribute()
    {
        // ログインしていない場合はfalse
        if (Auth::guest()) {
            return false;
        }

        // お気に入りに自分のユーザーIDが含まれていたらtrue
        return $this->likes->contains(function ($user) {
            return $user->id === Auth::user()->id;
        });
    }

    /**
     * アクセサ 応募数
     *
     * @return int
     */
    public function getApplicationsCountAttribute()
    {
        return $this->applications()->count();
    }

    /**
     * アクセサ 自分が応募しているか
     *
     * @return string
     */
    public function getIsApplicationAttribute()
    {
        // ログインしていない場合はfalse
        if (Auth::guest()) {
            return false;
        }

        // 応募に自分のユーザーIDが含まれていたらtrue
        return $this->applications()->where('user_id', Auth::user()->id)->exists();
    }

    /**
     * アクセサ　NEW表示判定
     *
     * 投稿されてから3日間はNEWバッジを付ける
     *
     * @return boolean
     */
    public function getIsNewAttribute()
    {
        return $this->attributes['created_at'] > Carbon::now()->subDay(3);
    }

    /**
     * アクセサ 編集する際の金額/%（最小）
     *
     * 保存するときに単発を1000倍しているため、編集する際はもとに戻す
     *
     * @return integer
     */
    public function getNumberMinEditAttribute()
    {
        // 単発：1/1000、レベニューシェア：1倍（変更なし）
        $rate = $this->attributes['type'] === 0 ? 1000 : 1;
        return  $this->attributes['number_min'] / $rate;
    }

    /**
     * アクセサ 編集する際の金額/%（最大）
     *
     * 保存するときに単発を1000倍しているため、編集する際はもとに戻す
     *
     * @return integer
     */
    public function getNumberMaxEditAttribute()
    {
        // 単発：1/1000、レベニューシェア：1倍（変更なし）
        $rate = $this->attributes['type'] === 0 ? 1000 : 1;
        return $this->attributes['number_max'] / $rate;
    }
}
