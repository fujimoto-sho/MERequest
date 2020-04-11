<?php

use App\Models\DirectMessage;
use App\Models\Proposition;
use Illuminate\Database\Seeder;

/**
 * シーダー ダイレクトメッセージ
 */
class DirectMessagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 各案件にダイレクトメッセージを追加する
        $propositions = Proposition::all();
        foreach ($propositions as $proposition) {

            // 案件応募者とのダイレクトメッセージを作成する
            // 案件応募者がいなかったらダイレクトメッセージを作成しない
            foreach ($proposition->applications as $application) {
                // 1/2の確率で作成する
                if (mt_rand(0, 1) === 0) {
                    continue;
                }

                // 案件投稿者のメッセージ投稿
                factory(DirectMessage::class, 3)->create([
                    'application_id' => $application->id,
                    'user_id'        => $proposition->user_id,
                ]);

                // 案件応募者のメッセージ投稿
                factory(DirectMessage::class, 4)->create([
                    'application_id' => $application->id,
                    'user_id'        => $application->user->id,
                ]);
            }
        }
    }
}
