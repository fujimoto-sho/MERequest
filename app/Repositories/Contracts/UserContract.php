<?php

namespace App\Repositories\Contracts;

/**
 * リポジトリパターン Interface ユーザー
 *
 * データソースに対して共通のインターフェイスを定義する
 *
 * @package App\Repositories\Contracts
 */
interface UserContract
{
    /**
     * マイページに表示するユーザー情報を取得する
     *
     * @param int $id ユーザーID
     * @return mixed
     */
    public function mypage(int $id);

    /**
     * パスワードを変更する
     *
     * @param string $password パスワード
     * @return bool
     * @throws \Exception
     */
    public function passwordChange(string $password);

    /**
     * 退会する
     *
     * ユーザーテーブル自体は削除しない
     * 退会ユーザーテーブルに追加し、ログインをできなくする
     *
     * プロフィール、お気に入りは削除する
     *
     * @throws \Exception
     */
    public function withdraw();

    /**
     * ユーザー情報を更新する
     *
     * @param array $params パラメータ
     * @param int $id ユーザーID
     * @throws \Exception
     */
    public function update(array $params, int $id);

    /**
     * ログインユーザー情報を返却する
     *
     * @return mixed
     */
    public function loginUser();
}
