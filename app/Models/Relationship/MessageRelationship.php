<?php

namespace App\Models\Relationship;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * messagesテーブルのリレーション設定
 *
 * @package App\Models\Relationship
 */
trait MessageRelationship
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
}
