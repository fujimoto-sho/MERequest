<?php

namespace App\Models;

use App\Models\Relationship\MessageRelationship;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * messagesテーブルのモデル
 *
 * @package App\Models
 */
class Message extends Model
{
    use MessageRelationship,
        softDeletes;

    /**
     * 複数代入する属性
     *
     * @var array
     */
    protected $fillable = [
        'proposition_id',
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
        'proposition_id',
        'user_id',
        'message',
        'created_at',
        'user',
    ];
}
