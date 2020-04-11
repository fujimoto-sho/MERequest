<?php

namespace App\Repositories\Contracts;

/**
 * リポジトリパターン Interface 案件
 *
 * データソースに対して共通のインターフェイスを定義する
 *
 * @package App\Repositories\Contracts
 */
interface PropositionContract
{
    /**
     * 指定されたidの案件を取得
     *
     * @param int $id
     * @return mixed
     */
    public function find(int $id);

    /**
     * 指定されたidの成約情報を返却する
     * 指定されたidが存在しなかったら、Exceptionを発生させる
     *
     * @param int $id 案件ID
     * @return mixed
     * @throws \Exception
     */
    public function findOrThrowException(int $id);

    /**
     * 案件一覧に必要な情報を返却する
     *
     * 検索条件が入力されていたら、それにマッチする案件を取得し、返却する
     *
     * @param array $param パラメータ
     * @return mixed
     */
    public function index(array $param);

    /**
     * 案件詳細に必要な情報を返却する
     *
     * @param int $id 案件ID
     * @return mixed
     */
    public function show(int $id);

    /**
     * 案件を投稿し、その情報を返却する
     *
     * @param array $params パラメータ
     * @return mixed
     * @throws  \Exception
     */
    public function store(array $params);

    /**
     * 案件を更新し、その情報を返却する
     *
     * @param int $id 案件ID
     * @param array $params パラメータ
     * @return mixed
     * @throws \Exception
     */
    public function update(int $id, array $params);

    /**
     * 案件に応募し、その案件IDを返す
     *
     * @param int $proposition_id 案件ID
     * @param int $user_id ユーザーID
     * @return mixed
     * @throws \Exception
     */
    public function application(int $proposition_id, int $user_id);

    /**
     * お気に入りを登録し、その案件IDを返す
     *
     * @param int $proposition_id 案件ID
     * @param int $user_id ユーザーID
     * @return mixed
     * @throws \Exception
     */
    public function like(int $proposition_id, int $user_id);

    /**
     * お気に入りを削除し、その案件IDを返す
     *
     * @param int $proposition_id 案件ID
     * @param int $user_id ユーザーID
     * @return mixed
     * @throws \Exception
     */
    public function unlike(int $proposition_id, int $user_id);

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
    public function fetchMypageProposition(int $user_id, array $param);
}
