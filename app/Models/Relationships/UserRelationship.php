<?php

namespace App\Models\Relationship;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * usersテーブルのリレーション設定
 *
 * @package App\Models\Relationship
 */
trait UserRelationship
{
    /**
     * リレーションシップ applicationsテーブル
     *
     * @return belongsToMany
     */
    public function applications()
    {
        return $this->belongsTo('App\Models\Application');
    }

    /**
     * リレーションシップ direct_messagesテーブル
     *
     * @return hasMany
     */
    public function direct_messages()
    {
        return $this->hasMany('App\Models\DirectMessage');
    }

    /**
     * リレーションシップ likesテーブル
     *
     * @return belongsToMany
     */
    public function likes()
    {
        return $this->belongsToMany('App\Models\Proposition', 'likes')->withTimestamps();
    }

    /**
     * リレーションシップ messagesテーブル
     *
     * @return hasMany
     */
    public function messages()
    {
        return $this->hasMany('App\Models\Message');
    }

    /**
     * リレーションシップ profilesテーブル
     *
     * @return hasOne
     */
    public function profile()
    {
        return $this->hasOne('App\Models\Profile');
    }

    /**
     * リレーションシップ propositionsテーブル
     *
     * @return hasMany
     */
    public function propositions()
    {
        return $this->hasMany('App\Models\Proposition');
    }
}
