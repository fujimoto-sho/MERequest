<?php

namespace App\Repositories\Contracts;

/**
 * リポジトリパターン Interface パブリックメッセージ
 *
 * データソースに対して共通のインターフェイスを定義する
 *
 * @package App\Repositories\Contracts
 */
interface MessageContract
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
    public function store(array $params);
}
