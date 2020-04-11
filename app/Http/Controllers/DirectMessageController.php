<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDirectMessageRequest;
use App\Repositories\Contracts\DirectMessageContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * ダイレクトメッセージコントローラー
 *
 * @property DirectMessageContract direct_message
 * @package App\Http\Controllers
 */
class DirectMessageController extends Controller
{
    /**
     * 初期処理
     *
     * @param DirectMessageContract $direct_message ダイレクトメッセージ
     */
    public function __construct(DirectMessageContract $direct_message)
    {
        $this->direct_message = $direct_message;
    }

    /**
     * ダイレクトメッセージ一覧の表示に必要な情報を返す
     *
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        return $this->direct_message->index($request->per_page ?? 10);
    }

    /**
     * ダイレクトメッセージ詳細の表示に必要な情報を返す
     *
     * @param int $application_id 応募ID
     * @return mixed
     * @throws \Exception
     */
    public function show(int $application_id)
    {
        // ダイレクトメッセージ詳細に必要な情報を返す
        $application = $this->direct_message->show($application_id);

        // 対象データが存在しなかった場合、例外を発生させる
        return $application ?? abort(404);
    }

    /**
     * ダイレクトメッセージ投稿
     *
     * 登録に成功したら、そのメッセージ情報を返す
     *
     * @param StoreDirectMessageRequest $request フォームリクエスト
     * @param int $application_id 応募ID
     * @return mixed
     * @throws \Exception
     */
    public function store(StoreDirectMessageRequest $request, int $application_id)
    {
        // 登録に必要な情報を格納する
        $params['application_id'] = $application_id;
        $params['user_id'] = Auth::user()->id;
        $params['message'] = $request->message;

        // ダイレクトメッセージ投稿処理
        $new_message = $this->direct_message->store($params);

        // 投稿したダイレクトメッセージ情報を返す
        return response($new_message, 201);
    }
}
