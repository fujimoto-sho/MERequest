<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePropositionRequest;
use App\Mail\ApplicationNotification;
use App\Repositories\Contracts\PropositionContract;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

/**
 * 案件コントローラー
 *
 * @property PropositionContract proposition
 * @package App\Http\Controllers
 */
class PropositionController extends Controller
{
    /**
     * 初期処理
     *
     * @param PropositionContract $proposition 案件
     */
    public function __construct(PropositionContract $proposition)
    {
        $this->proposition = $proposition;
    }

    /**
     * 案件一覧の表示に必要な情報を返す
     *
     * @param Request $request
     * @return mixed
     */
    /**
     * @OA\Info(title="MERequestのAPIドキュメント", version="1.0")
     */

    /**
     * @OA\Get(
     *     path="/api/propositions",
     *     @OA\Parameter(
     *         name="free_word",
     *         description="検索項目：フリーワード",
     *         in="query",
     *         required=false,
     *         type="string"
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         description="検索項目：募集状況",
     *         in="query",
     *         required=false,
     *         type="boolean"
     *     ),
     *     @OA\Parameter(
     *         name="type",
     *         description="検索項目：案件種別",
     *         in="query",
     *         required=false,
     *         type="number"
     *     ),
     *     @OA\Parameter(
     *         name="single_number_min",
     *         description="検索項目：金額（最小）",
     *         in="query",
     *         required=false,
     *         type="number"
     *     ),
     *     @OA\Parameter(
     *         name="single_number_max",
     *         description="検索項目：金額（最大）",
     *         in="query",
     *         required=false,
     *         type="number"
     *     ),
     *     @OA\Parameter(
     *         name="revenue_number_min",
     *         description="検索項目：レベニューシェア配分率（最小）",
     *         in="query",
     *         required=false,
     *         type="number"
     *     ),
     *     @OA\Parameter(
     *         name="revenue_number_max",
     *         description="検索項目：レベニューシェア配分率（最大）",
     *         in="query",
     *         required=false,
     *         type="number"
     *     ),
     *     @OA\Parameter(
     *         name="order",
     *         description="検索結果の並び順",
     *         in="query",
     *         required=false,
     *         type="string"
     *     ),
     *     @OA\Response(response="200", description="案件一覧を取得する"),
     *     @OA\Response(response="404", description="NG")
     * )
     */
    public function index(Request $request)
    {
        return $this->proposition->index($request->all());
    }

    /**
     * 案件詳細の表示に必要な情報を返す
     *
     * @param int $proposition_id 案件ID
     * @return void
     */
    public function show(int $proposition_id)
    {
        $proposition = $this->proposition->show($proposition_id);

        // 対象データが存在しなかった場合、例外を発生させる
        return $proposition ?? abort(404);
    }

    /**
     * 案件を新規作成する
     *
     * @param StorePropositionRequest $request フォームリクエスト
     * @return Response
     * @throws \Exception
     */
    public function store(StorePropositionRequest $request)
    {
        // 登録に必要な情報を格納する
        $params = $request->all();
        $params['user_id'] = Auth::user()->id;

        // 案件登録処理
        $proposition = $this->proposition->store($params);

        return response($proposition, 201);
    }

    /**
     * 案件編集の表示に必要な情報を返す
     *
     * 該当する案件がなかったら、例外を返す
     *
     * @param int $proposition_id 案件ID
     * @return void
     */
    public function edit(int $proposition_id)
    {
        // 案件の情報を取得する
        $proposition = $this->proposition->find($proposition_id);

        // 該当案件なし、またはログインユーザーが作成した案件以外は例外を発生させる
        if ($proposition === null || $proposition->doesntExist() || $proposition->user_id !== Auth::user()->id) {
            return abort(404);
        }

        // 案件情報を返す
        return $proposition;
    }

    /**
     * 案件を更新する
     *
     * @param StorePropositionRequest $request フォームリクエスト
     * @param int $proposition_id 案件ID
     * @return Response
     * @throws \Exception
     */
    public function update(StorePropositionRequest $request, int $proposition_id)
    {
        // 更新に必要な情報を格納する
        $params = $request->all();
        $params['user_id'] = Auth::user()->id;

        // 案件更新処理
        $this->proposition->update($proposition_id, $params);
    }

    /**
     * 案件に応募する
     *
     * @param int $proposition_id 案件ID
     * @return array|void
     * @throws \Exception
     */
    public function application(int $proposition_id)
    {
        // 案件応募
        $result_proposition_id = $this->proposition->application($proposition_id, Auth::user()->id);

        // 応募対象が存在しなかった場合、例外を発生させる
        if (empty($result_proposition_id)) {
            abort(404);
        }

        $proposition = $this->proposition->find($proposition_id);
        $proposition_title = $proposition->title;
        $opponent_user_name = Auth::user()->name;
        $to = $proposition->user->email;
        // 案件投稿者に応募があった旨のメールを送信
        Mail::to($to)->send(new ApplicationNotification($proposition_title, $opponent_user_name));

        return ['proposition_id' => $result_proposition_id];
    }

    /**
     * お気に入り登録
     *
     * @param int $proposition_id
     * @return mixed|void
     * @throws \Exception
     */
    public function like(int $proposition_id)
    {
        // お気に入り登録
        $result_proposition_id = $this->proposition->like($proposition_id, Auth::user()->id);

        // 対象データが存在しなかった場合、例外を発生させる
        return ['proposition_id' => $result_proposition_id] ?? abort(404);
    }

    /**
     * お気に入り削除
     *
     * @param int $proposition_id
     * @return mixed|void
     * @throws \Exception
     */
    public function unlike(int $proposition_id)
    {
        // お気に入り削除
        $result_proposition_id = $this->proposition->unlike($proposition_id, Auth::user()->id);

        // 対象データが存在しなかった場合、例外を発生させる
        return ['proposition_id' => $result_proposition_id] ?? abort(404);
    }

    /**
     * マイページに表示する案件の一覧情報を返す
     *
     * パラメータによって、以下のいずれかを返す
     * ・投稿した案件
     * ・応募した案件
     * ・パブリックメッセージを投稿した案件
     * ・お気に入り登録した案件
     *
     * @param Request $request
     * @param int $user_id ユーザーID
     * @return mixed
     */
    public function fetchMypageProposition(Request $request, int $user_id)
    {
        return $this->proposition->fetchMypageProposition($user_id, $request->all());
    }
}
