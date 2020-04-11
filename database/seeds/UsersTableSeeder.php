<?php

use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * シーダー ユーザー
 */
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ユーザを作成
        User::create([
            'name'     => 'テストユーザー',
            'email'    => 'test@test.com',
            'password' => bcrypt('password'),
        ]);

        // 10レコード作成
        factory(User::class, 10)->create();
    }
}
