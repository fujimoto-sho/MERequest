<?php

namespace App\Repositories\Eloquents;

use App\Models\Application;
use App\Models\Proposition;
use App\Repositories\Contracts\PropositionContract;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * リポジトリパターン Eloquent 案件
 *
 * インターフェイスをEloquentで実装する
 *
 * @package App\Repositories\Eloquents
 */
class EloquentPropositionRepository implements PropositionContract
{
    /**
     * 指定されたidの案件を返却する
     *
     * @param int $id
     * @return mixed
     */
    public function find(int $id)
    {
        return Proposition::find($id);
    }

    /**
     * 指定されたidの成約情報を返却する
     * 指定されたidが存在しなかったら、Exceptionを発生させる
     *
     * @param int $id 案件ID
     * @return mixed
     * @throws \Exception
     */
    public function findOrThrowException(int $id)
    {
        $proposition = Proposition::find($id);
        if (empty($proposition)) {
            throw new \Exception('案件情報が見つかりませんでした', 404);
        }
        return $proposition;
    }

    /**
     * 案件一覧に必要な情報を返却する
     *
     * 検索条件が入力されていたら、それにマッチする案件を取得し、返却する
     *
     * @param array $param パラメータ
     * @return mixed
     */
    public function index(array $param)
    {
        $propositions = Proposition::with(['user.profile', 'messages', 'likes']);

        // フリーワード
        if (isset($param['free_word'])) {
            $free_word = $param['free_word'];
            $propositions = $propositions->where(function ($query) use ($free_word) {
                $query->where('title', 'like', '%' . $free_word . '%')
                    ->orWhere('content', 'like', '%' . $free_word . '%');
            });
        }

        // 募集状況
        if (isset($param['status'])) {
            $propositions = $propositions->where('status', $param['status']);
        }

        // 案件種別
        if (isset($param['type'])) {
            $propositions = $propositions->where('type', $param['type']);
        }

        // 金額（最小）
        if (isset($param['single_number_min'])) {
            $single_number_min = $param['single_number_min'];
            $propositions = $propositions->where(function ($query) use ($single_number_min) {
                $query->where('type', '<>', 0)
                    ->orWhere(function ($query) use ($single_number_min) {
                        $query->where('number_min', '>=', $single_number_min)
                            ->where('number_max', '>=', $single_number_min);
                    });
            });
        }

        // 金額（最大）
        if (isset($param['single_number_max'])) {
            $single_number_max = $param['single_number_max'];
            $propositions = $propositions->where(function ($query) use ($single_number_max) {
                $query->where('type', '<>', 0)
                    ->orWhere(function ($query) use ($single_number_max) {
                        $query->where('number_min', '<=', $single_number_max)
                            ->where('number_max', '<=', $single_number_max);
                    });
            });
        }

        // レベニューシェア配分率（最小）
        if (isset($param['revenue_number_min'])) {
            $revenue_number_min = $param['revenue_number_min'];
            $propositions = $propositions->where(function ($query) use ($revenue_number_min) {
                $query->where('type', '<>', 1)
                    ->orWhere(function ($query) use ($revenue_number_min) {
                        $query->where('number_min', '>=', $revenue_number_min)
                            ->where('number_max', '>=', $revenue_number_min);
                    });
            });
        }

        // レベニューシェア配分率（最大）
        if (isset($param['revenue_number_max'])) {
            $revenue_number_max = $param['revenue_number_max'];
            $propositions = $propositions->where(function ($query) use ($revenue_number_max) {
                $query->where('type', '<>', 1)
                    ->orWhere(function ($query) use ($revenue_number_max) {
                        $query->where('number_min', '<=', $revenue_number_max)
                            ->where('number_max', '<=', $revenue_number_max);
                    });
            });
        }

        // 並び順
        $order = $param['order'] ?? 'desc';
        $propositions = $propositions->orderBy(Proposition::CREATED_AT, $order);

        return $propositions->paginate();
    }

    /**
     * 案件詳細に必要な情報を返却する
     *
     * 応募情報は、自分に関係あるもののみに制限する
     *
     * @param int $id 案件ID
     * @return mixed
     */
    public function show(int $id)
    {
        return Proposition::where('id', $id)
            ->with([
                'user.profile',
                'messages.user.profile',
                'likes',
                'applications' => function ($query) {
                    $query->where('user_id', Auth::user()->id ?? 0)
                        ->orWhereHas('proposition', function ($query) {
                            $query->where('user_id', Auth::user()->id ?? 0);
                        });
                },
            ])
            ->first();
    }

    /**
     * 案件を投稿し、その情報を返却する
     *
     * @param array $params パラメータ
     * @return mixed
     * @throws  \Exception
     */
    public function store(array $params)
    {
        DB::beginTransaction();
        try {
            // 案件登録
            $proposition = new Proposition();
            $proposition->fill($params);
            $proposition->save();

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception);

            throw new \Exception('案件の投稿に失敗しました', 500);
        }

        return $proposition;
    }

    /**
     * 案件を更新し、その情報を返却する
     *
     * @param int $id 案件ID
     * @param array $params パラメータ
     * @return mixed
     * @throws \Exception
     */
    public function update(int $id, array $params)
    {
        DB::beginTransaction();
        try {
            // 案件登録
            $proposition = $this->findOrThrowException($id);
            $proposition->fill($params);
            $proposition->save();

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception);

            throw new \Exception('案件の投稿に失敗しました', 500);
        }

        return $proposition;
    }

    /**
     * 案件に応募し、その案件IDを返す
     *
     * @param int $proposition_id 案件ID
     * @param int $user_id ユーザーID
     * @return mixed
     * @throws \Exception
     */
    public function application(int $proposition_id, int $user_id)
    {
        // 対象の案件を取得
        $proposition = Proposition::where('id', $proposition_id)->with(['applications'])->first();

        // 案件が存在しなかった場合、nullを返す
        if (!$proposition) {
            return;
        }

        DB::beginTransaction();
        try {

            // 多重応募にならないように、DELETE→INSERTを行う
            $proposition->applications()->where('user_id', $user_id)->delete();
            $proposition->applications()->create(['user_id' => $user_id]);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception);

            throw new \Exception('案件の応募に失敗しました', 500);
        }

        return $proposition_id;
    }

    /**
     * お気に入りを登録し、その案件IDを返す
     *
     * @param int $proposition_id 案件ID
     * @param int $user_id ユーザーID
     * @return mixed
     * @throws \Exception
     */
    public function like(int $proposition_id, int $user_id)
    {
        // 対象の案件を取得
        $proposition = Proposition::where('id', $proposition_id)->with(['likes'])->first();

        // 案件が存在しなかった場合、nullを返す
        if (!$proposition) {
            return;
        }

        DB::beginTransaction();
        try {

            // 2個お気に入りがつかないように、DELETE→INSERTを行う
            $proposition->likes()->detach($user_id);
            $proposition->likes()->attach($user_id);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception);

            throw new \Exception('お気に入りの登録に失敗しました', 500);
        }

        return $proposition_id;
    }

    /**
     * お気に入りを削除し、その案件IDを返す
     *
     * @param int $proposition_id 案件ID
     * @param int $user_id ユーザーID
     * @return mixed
     * @throws \Exception
     */
    public function unlike(int $proposition_id, int $user_id)
    {
        // 対象の案件を取得
        $proposition = Proposition::where('id', $proposition_id)->with(['likes'])->first();

        // 案件が存在しなかった場合、nullを返す
        if (!$proposition) {
            return;
        }

        DB::beginTransaction();
        try {

            // お気に入り削除
            $proposition->likes()->detach($user_id);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception);

            throw new \Exception('お気に入りの削除に失敗しました', 500);
        }

        return $proposition_id;
    }

    /**
     * マイページから遷移できる、案件の一覧情報を返却する
     *
     * パラメータにセットされている内容によって、取得する案件が変わる
     * - is_post        ：投稿した案件
     * - is_application ：応募した案件
     * - is_message     ：パブリックメッセージを投稿した案件
     * - is_like        ：お気に入り登録した案件
     *
     * @param int $user_id ユーザーID
     * @param array $param パラメータ
     * @return mixed
     */
    public function fetchMypageProposition(int $user_id, array $param)
    {
        $propositions = Proposition::with(['user.profile', 'messages', 'likes', 'applications.direct_messages']);
        $propositions = $propositions->select('propositions.*');

        // 投稿した案件
        if (isset($param['is_post'])) {
            $propositions = $propositions->where('user_id', $user_id);

            // 並び順
            $propositions = $propositions->orderBy(Proposition::CREATED_AT, 'desc');
        }

        // 応募した案件
        if (isset($param['is_application'])) {
            $propositions = $propositions->join('applications', function ($join) use ($user_id) {
                $join->on('applications.proposition_id', '=', 'propositions.id')
                    ->where('applications.user_id', '=', $user_id);
            });

            // 並び順
            $propositions = $propositions->orderBy('applications.created_at', 'desc');
        }

        // パブリックメッセージを投稿した案件
        if (isset($param['is_message'])) {
            // 案件ごとの最新のパブリックメッセージを取得
            $latest_messages = DB::table('messages')
                ->select('proposition_id', DB::raw('MAX(created_at) as last_message_created_at'))
                ->where('user_id', $user_id)
                ->groupBy('proposition_id');

            // 最新のパブリックメッセージと比較する
            $propositions = $propositions->joinSub($latest_messages, 'latest_messages', function ($join) {
                $join->on('latest_messages.proposition_id', '=', 'propositions.id');
            });

            // 並び順
            $propositions = $propositions->orderBy('last_message_created_at', 'desc');
        }

        // お気に入り登録した案件
        if (isset($param['is_like'])) {
            $propositions = $propositions->join('likes', 'likes.proposition_id', '=', 'propositions.id');
            $propositions = $propositions->where('likes.user_id', $user_id);

            // 並び順
            $propositions = $propositions->orderBy('likes.created_at', 'desc');
        }

        // 取得する最大件数
        $per_page = 10; // デフォルトは10件
        if (isset($param['per_page'])) {
            $per_page = $param['per_page'];
        }

        return $propositions->paginate($per_page);
    }
}
