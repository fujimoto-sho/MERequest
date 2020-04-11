<?php

namespace App\Models;

use App\Models\Accessors\PropositionAccessor;
use App\Models\Mutators\PropositionMutator;
use App\Models\Relationship\PropositionRelationship;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * propositionsテーブルのモデル
 *
 * @package App\Models
 */
class Proposition extends Model
{
    use PropositionRelationship,
        PropositionAccessor,
        PropositionMutator,
        softDeletes;

    /**
     * 複数代入する属性
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'title',
        'type',
        'number_min',
        'number_max',
        'recruiting_count',
        'content',
        'status',
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
     * JSONに追加する属性
     *
     * @var array
     */
    protected $appends = [
        'type_name',
        'status_name',
        'formatted_number_min',
        'formatted_number_max',
        'publication_date',
        'likes_count',
        'is_like',
        'applications_count',
        'is_application',
        'is_new',
        'number_min_edit',
        'number_max_edit',
    ];

    /**
     * JSONに含める属性
     *
     * @var array
     */
    protected $visible = [
        'id',
        'user_id',
        'title',
        'type',
        'number_min',
        'number_max',
        'recruiting_count',
        'content',
        'status',
        'created_at',
        'type_name',
        'status_name',
        'formatted_number_min',
        'formatted_number_max',
        'publication_date',
        'likes_count',
        'is_like',
        'applications_count',
        'is_application',
        'is_new',
        'number_min_edit',
        'number_max_edit',
        'user',
        'messages',
        'applications',
    ];

    /**
     * ページネーションの数
     *
     * @var int
     */
    protected $perPage = 10;
}
