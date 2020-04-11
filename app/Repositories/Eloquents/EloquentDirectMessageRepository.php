<?php

namespace App\Repositories\Eloquents;

use App\Models\Application;
use App\Models\DirectMessage;
use App\Repositories\Contracts\DirectMessageContract;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * リポジトリパターン Eloquent ダイレクトメッセージ
 *
 * インターフェイスをEloquentで実装する
 *
 * @package App\Repositories\Eloquents
 */
class EloquentDirectMessageRepository implements DirectMessageContract
{
    /**
     * ダイレクトメッセージ一覧に必要な情報を返却する
     *
     * ダイレクトメッセージは、応募情報(applicationsテーブル)を元に作成されているため、
     * 応募情報の一覧を返却する
     *
     * 投稿者、または応募者のいずれかにログインユーザーが含まれているものだけを取得する
     *
     * @param int $per_page 取得する件数
     * @return mixed
     */
    public function index(int $per_page)
    {
        // ログインユーザーのID
        $user_id = Auth::user()->id;

        // 案件ごとの最新ダイレクトメッセージを取得
        $latest_direct_messages = DB::table('direct_messages')
            ->select('application_id', DB::raw('MAX(created_at) as last_direct_message_created_at'))
            ->groupBy('application_id');

        // 応募情報
        $applications = Application::with(['proposition.user.profile', 'direct_messages', 'user.profile']);
        $applications = $applications->select('applications.*');
        $applications = $applications->leftJoin('propositions', 'propositions.id', '=', 'applications.proposition_id');
        $applications = $applications->leftJoinSub($latest_direct_messages, 'latest_direct_messages', function ($join) {
            $join->on('latest_direct_messages.application_id', '=', 'applications.id');
        });

        $applications = $applications->where('applications.user_id', $user_id)
            ->orWhere('propositions.user_id', $user_id);

        // 並び順
        // メッセージがない場合は、応募時間を使用して並び替えを行う
        $applications = $applications->orderByRaw('CASE WHEN last_direct_message_created_at is not null THEN last_direct_message_created_at ELSE applications.created_at END DESC');

        return $applications->paginate($per_page);
    }

    /**
     * ダイレクトメッセージ詳細に必要な情報を返却する
     *
     * 指定された応募IDに紐づくダイレクトメッセージを返却する
     *
     * 投稿者、または応募者のいずれかにログインユーザーが含まれているものだけを取得する
     *
     * @param int $application_id 応募ID
     * @return mixed
     * @throws  \Exception
     */
    public function show(int $application_id)
    {
        return Application::with(['proposition.user.profile', 'user.profile', 'direct_messages.user.profile'])
            ->where('id', $application_id)
            // 投稿者、または応募者のいずれかにログインユーザーが含まれているものだけを取得する
            ->where(function ($query) {
                $query->where('user_id', Auth::user()->id)
                    ->orWhereHas('proposition', function ($query) {
                        $query->where('user_id', Auth::user()->id);
                    });
            })
            ->first();
    }

    /**
     * ダイレクトメッセージ投稿
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
            $message = new DirectMessage();
            $message->fill($params);
            $message->save();

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception);

            throw new \Exception('ダイレクトメッセージの投稿に失敗しました', 500);
        }

        // リレーション先も取得するため、再取得して返却する
        return DirectMessage::where('id', $message->id)
            ->with(['user.profile'])
            ->first();
    }
}
