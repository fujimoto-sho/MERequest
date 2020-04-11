<?php

namespace App\Models\Relationship;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * profilesテーブルのリレーション設定
 *
 * @package App\Models\Relationship
 */
trait ProfileRelationship
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
}
