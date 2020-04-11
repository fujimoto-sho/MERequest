<?php

use App\Models\Proposition;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * シーダー 応募
 */
class ApplicationsTableSeeder extends Seeder
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
            // 全ユーザー分ループする
            for ($user_id = 1; $user_id <= $user_count; $user_id++) {
                // 案件登録ユーザーは対象外
                if ($user_id === $proposition->user_id) {
                    continue;
                }

                // 1/2の確率で案件応募する
                if (mt_rand(0, 1) === 0) {
                    $proposition->applications()->create([
                        'user_id' => $user_id,
                    ]);
                }
            }
        }
    }
}
