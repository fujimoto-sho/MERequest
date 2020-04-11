<?php

use App\Models\DirectMessage;
use App\Models\Message;
use App\Models\Profile;
use App\Models\Proposition;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * シーダー 全テーブルのデータを削除
 */
class TruncateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 外部キー制約解除
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 全テーブルのデータを削除
        User::truncate();
        Profile::truncate();
        Proposition::truncate();
        Message::truncate();
        DirectMessage::truncate();
        DB::table('likes')->truncate();
        DB::table('applications')->truncate();
        DB::table('password_resets')->truncate();

        // 外部キー制約設定
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
