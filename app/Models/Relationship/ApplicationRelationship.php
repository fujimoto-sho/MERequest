<?php

namespace App\Models\Relationship;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * applicationsテーブルのリレーション設定
 *
 * @package App\Models\Relationship
 */
trait ApplicationRelationship
{
    /**
     * リレーションシップ propositionsテーブル
     *
     * @return belongsTo
     */
    public function proposition()
    {
        return $this->belongsTo('App\Models\Proposition');
    }

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
     * リレーションシップ direct_messagesテーブル
     *
     * @return hasMany
     */
    public function direct_messages()
    {
        return $this->hasMany('App\Models\DirectMessage');
    }
}
