<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Repositories\Contracts\MessageContract;
use Illuminate\Support\Facades\Auth;

/**
 * パブリックメッセージコントローラー
 *
 * @property MessageContract message
 * @package App\Http\Controllers
 */
class MessageController extends Controller
{
    /**
     * 初期処理
     *
     * @param MessageContract $message パブリックメッセージ
     */
    public function __construct(MessageContract $message)
    {
        $this->message = $message;
    }

    /**
     * パブリックメッセージを投稿する
     *
     * 登録に成功したら、そのメッセージ情報を返す
     *
     * @param StoreMessageRequest $request フォームリクエスト
     * @param int $proposition_id 案件ID
     * @return mixed
     * @throws \Exception
     */
    public function store(StoreMessageRequest $request, int $proposition_id)
    {
        // パブリックメッセージ投稿に必要な情報を変数に格納する
        $params['proposition_id'] = $proposition_id;
        $params['user_id'] = Auth::user()->id;
        $params['message'] = $request->message;

        // パブリックメッセージ投稿処理
        $new_message = $this->message->store($params);

        // 投稿したパブリックメッセージ情報を返す
        return response($new_message, 201);
    }
}
