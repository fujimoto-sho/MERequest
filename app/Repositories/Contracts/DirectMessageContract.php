<?php

namespace App\Repositories\Contracts;

/**
 * リポジトリパターン Interface ダイレクトメッセージ
 *
 * データソースに対して共通のインターフェイスを定義する
 *
 * @package App\Repositories\Contracts
 */
interface DirectMessageContract
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
    public function index(int $per_page);

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
    public function show(int $application_id);

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
    public function store(array $params);
}
