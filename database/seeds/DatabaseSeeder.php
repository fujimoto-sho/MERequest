<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 全テーブルのデータを削除
        $this->call(TruncateTableSeeder::class);
        // データ作成
        $this->call(UsersTableSeeder::class);
        $this->call(ProfilesTableSeeder::class);
        $this->call(PropositionsTableSeeder::class);
        $this->call(LikesTableSeeder::class);
        $this->call(ApplicationsTableSeeder::class);
        $this->call(MessagesTableSeeder::class);
        $this->call(DirectMessagesTableSeeder::class);
    }
}
