<?php

namespace App\Models\Relationship;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * direct_messagesテーブルのリレーション設定
 *
 * @package App\Models\Relationship
 */
trait DirectMessageRelationship
{
    /**
     * リレーションシップ applicationsテーブル
     *
     * @return belongsTo
     */
    public function application()
    {
        return $this->belongsTo('App\Models\Application');
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
