<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePasswordChangeRequest;
use App\Http\Requests\StoreUserRequest;
use App\Repositories\Contracts\UserContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * ユーザーコントローラー
 *
 * @property UserContract user
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    /**
     * 初期処理
     *
     * @param UserContract $user ユーザー
     */
    public function __construct(UserContract $user)
    {
        $this->user = $user;
    }

    /**
     * パスワード変更
     *
     * @param StorePasswordChangeRequest $request フォームリクエスト
     * @throws \Exception
     */
    public function passwordChange(StorePasswordChangeRequest $request)
    {
        $this->user->passwordChange($request->password);
    }

    /**
     * 退会を行う
     */
    public function withdraw()
    {
        // 募集中の案件がある場合は退会不可
        $is_under_recruitment_proposition = Auth::user()->propositions()->where('status', 0)->exists();
        if ($is_under_recruitment_proposition) {
            return abort(422, '投稿した案件に、募集中のものがあります');
        }

        // 退会処理
        $this->user->withdraw();

        // ログアウト
        Auth::logout();
    }

    /**
     * マイページに表示するユーザー情報を返す
     *
     * @param int $id ユーザーID
     * @return mixed
     */
    public function mypage(int $id)
    {
        // 存在しなかった場合、例外を発生させる
        return $this->user->mypage($id) ?? abort(404);
    }

    /**
     * プロフィール更新
     *
     * @param StoreUserRequest $request フォームリクエスト
     * @throws \Exception
     */
    public function update(StoreUserRequest $request)
    {
        $params = $request->all();

        // 画像がpostされた場合、
        // S3にアップロードし、そのパスをプロフィール格納用に変換する
        if (isset($request->image)) {
            // S3にファイルを保存し、ファイル名を取得する
//            $filename = Storage::cloud()->putFile('', $request->image, 'public');
            $filename = $request->image->store('public/avatar');
            $filename = str_replace('public/avatar/', '', $filename);
            // ファイル名をパラメータに追加する
            $params['image'] = $filename;
        }

        // プロフィールの更新を行う
        $this->user->update($params, Auth::user()->id);
    }

    /**
     * ログインしているユーザーの情報を取得
     *
     * @return mixed
     */
    public function user()
    {
        return $this->user->loginUser();
    }
}
