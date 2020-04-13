<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * 案件応募時に送信されるメール
 *
 * メールを送信する際の内容を設定する
 *
 * @package App\Mail
 */
class ApplicationNotification extends Mailable
{
    use Queueable, SerializesModels;

    /** @var string 案件名 */
    protected $proposition_title;

    /** @var string 相手ユーザー名 */
    protected $opponent_user_name;

    /**
     * 初期処理
     *
     * @param string $proposition_title 案件名
     * @param string $opponent_user_name 相手ユーザー名
     */
    public function __construct(string $proposition_title, string $opponent_user_name)
    {
        $this->proposition_title = $proposition_title;
        $this->opponent_user_name = $opponent_user_name;
    }

    /**
     * 送信メールの作成
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.application')
            ->subject('[MERequest] 案件の応募がありました')
            ->with([
                'proposition_title'  => $this->proposition_title,
                'opponent_user_name' => $this->opponent_user_name,
            ]);
    }
}
