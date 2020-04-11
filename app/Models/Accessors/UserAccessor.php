<?php

namespace App\Models\Accessors;

use App\Models\Profile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * usersテーブルのアクセサ
 *
 * 取得するカラムのフォーマットを定義する
 *
 * @package App\Models\Accessors
 */
trait UserAccessor
{
    /**
     * アクセサ アバターのURL
     *
     * @return string
     */
    public function getAvatarUrlAttribute()
    {
        // アバターのURLを取得
        $profile = Profile::where('user_id', $this->attributes['id'])->first();
        $image = isset($profile) ? $profile['image'] : null;

        // アバターが設定されていなかったら、デフォルトの画像URLを返す
        if ($image === null) {
            return asset('storage/avatar/default_avatar.png');
        }

        // 文字列が「http:」で始まっていたら、そのまま返す
        if (strpos($image, 'http') === 0) {
            return $image;
        }

        // それ以外は、設定されたアバターのURLを返す
        return asset('storage/avatar/'.$image);
    }

    /**
     * アクセサ 退会フラグ
     *
     * 退会していたらtrue
     *
     * @return string
     */
    public function getIsWithdrawAttribute()
    {
        // withdraw_usersテーブルに存在したら退会済とする
        return DB::table('withdraw_users')->where('email', $this->attributes['email'])->exists();
    }
}
