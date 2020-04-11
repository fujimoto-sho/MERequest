<?php

namespace App\Repositories\Eloquents;

use App\Models\Message;
use App\Repositories\Contracts\MessageContract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * リポジトリパターン Eloquent パブリックメッセージ
 *
 * インターフェイスをEloquentで実装する
 *
 * @package App\Repositories\Eloquents
 */
class EloquentMessageRepository implements MessageContract
{
    /**
     * パブリックメッセージ投稿
     *
     * メッセージの投稿を行ったあと、
     * 投稿したメッセージを再取得し、返却する
     *
     * @param array $params パラメータ
     * @return mixed
     * @throws  \Exception
     */
    public function store(array $params)
    {
        DB::beginTransaction();
        try {
            $message = new Message();
            $message->fill($params);
            $message->save();

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception);

            throw new \Exception('パブリックメッセージの投稿に失敗しました', 500);
        }

        // リレーション先も取得するため、再取得して返却する
        return Message::where('id', $message->id)
            ->with(['user.profile'])
            ->first();
    }
}
