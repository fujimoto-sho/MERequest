<?php

namespace App\Models;

use App\Models\Relationship\ApplicationRelationship;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * applicationsテーブルのモデル
 *
 * @package App\Models
 */
class Application extends Model
{
    use ApplicationRelationship,
        softDeletes;

    /**
     * 複数代入する属性
     *
     * @var array
     */
    protected $fillable = [
        'proposition_id',
        'user_id',
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
        'id',
        'proposition_id',
        'user_id',
        'created_at',
        'proposition',
        'user',
        'direct_messages',
    ];
}
