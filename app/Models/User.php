<?php

namespace App\Models;

use App\Models\Accessors\UserAccessor;
use App\Models\Relationship\UserRelationship;
use App\Notifications\CustomPasswordReset;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * usersテーブルのモデル
 *
 * @package App\Models
 */
class User extends Authenticatable
{
    use Notifiable,
        UserAccessor,
        UserRelationship,
        SoftDeletes;

    /**
     * 複数代入する属性
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
        'avatar_url',
        'is_withdraw',
    ];

    /**
     * JSONに含める属性
     *
     * @var array
     */
    protected $visible = [
        'id',
        'name',
        'email',
        'profile',
        'avatar_url',
        'is_withdraw',
    ];

    /**
     * パスワードリセット通知の送信
     *
     * @param string $token
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomPasswordReset($token));
    }
}
