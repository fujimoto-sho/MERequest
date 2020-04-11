<?php

use App\Models\Proposition;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * シーダー 案件
 */
class PropositionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 既存のユーザーをもとに、各10レコード作成
        $users = User::all();
        foreach ($users as $user) {
            factory(Proposition::class, 10)->create(['user_id' => $user->id]);
        }
    }
}
