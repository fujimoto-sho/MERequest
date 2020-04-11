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
     * 日付へキャストする属性
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
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
