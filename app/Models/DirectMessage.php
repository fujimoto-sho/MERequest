<?php

namespace App\Models;

use App\Models\Relationship\DirectMessageRelationship;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * direct_messagesテーブルのモデル
 *
 * @package App\Models
 */
class DirectMessage extends Model
{
    use DirectMessageRelationship,
        softDeletes;

    /**
     * 複数代入する属性
     *
     * @var array
     */
    protected $fillable = [
        'application_id',
        'user_id',
        'message',
    ];

    /**
     * 属性のキャスト
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * JSONに含める属性
     *
     * @var array
     */
    protected $visible = [
        'application_id',
        'user_id',
        'message',
        'created_at',
        'user',
    ];
}
