<?php

use App\Models\Message;
use App\Models\Proposition;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * シーダー パブリックメッセージ
 */
class MessagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ユーザ数を取得
        $user_count = User::all()->count();

        // 各案件に10件ずつメッセージを追加する
        $propositions = Proposition::all();
        foreach ($propositions as $proposition) {
            // メッセージ送信ユーザーをランダムにするため、forでループして作成する
            for ($i = 0; $i < 10; $i ++) {
                factory(Message::class)->create([
                    'proposition_id' => $proposition->id,
                    'user_id'        => mt_rand(1, $user_count), // ランダムなユーザーからメッセージを送信する
                ]);
            }
        }
    }
}
