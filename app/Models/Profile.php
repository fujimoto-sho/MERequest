<?php

namespace App\Models;

use App\Models\Relationship\ProfileRelationship;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * profilesテーブルのモデル
 *
 * @package App\Models
 */
class Profile extends Model
{
    use ProfileRelationship,
        softDeletes;

    /**
     * 複数代入する属性
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'bio',
        'image',
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
        'user_id',
        'bio',
        'image',
    ];
}
