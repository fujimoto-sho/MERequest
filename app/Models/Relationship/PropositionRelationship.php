<?php

namespace App\Models\Relationship;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * propositionsテーブルのリレーション設定
 *
 * @package App\Models\Relationship
 */
trait PropositionRelationship
{
    /**
     * リレーションシップ usersテーブル
     *
     * @return belongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * リレーションシップ applicationsテーブル
     *
     * @return hasMany
     */
    public function applications()
    {
        return $this->hasMany('App\Models\Application');
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
        return $this->belongsToMany('App\Models\User', 'likes')->withTimestamps();
    }

    /**
     * リレーションシップ messagesテーブル
     *
     * @return hasMany
     */
    public function messages()
    {
        return $this->hasMany('App\Models\Message')->orderBy('id', 'asc');
    }
}
