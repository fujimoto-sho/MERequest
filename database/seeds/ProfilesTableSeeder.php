<?php

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * シーダー プロフィール
 */
class ProfilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 既存のユーザーにプロフィールを追加する
        $users = User::all();
        foreach ($users as $user) {
            factory(Profile::class)->create(['user_id' => $user->id]);
        }
    }
}
