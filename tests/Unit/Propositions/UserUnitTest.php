<?php

namespace Tests\Unit\Propositions;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersUnitTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        // テストユーザ作成
        $this->user = factory(User::class)->create();
    }

    /**
     * 正しいメールアドレスとパスワードを入力すると、ログインができる
     */
    public function testUserCanLogin(): void
    {
        // ログイン
        $response = $this->post(route('login'), [
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        // レスポンスの検証
        $response->assertOk();

        // 指定したユーザーが認証されていることを確認
        $this->assertAuthenticatedAs($this->user);
    }

    /**
     * 間違ったメールアドレスを入力すると、ログインができない
     */
    public function testUserCantLoginEmail(): void
    {
        // ログイン
        $response = $this->post(route('login'), [
            'email' => 'NG@example.com',
            'password' => 'password',
        ]);

        // レスポンスの検証
        $response->assertStatus(302);

        // 指定したユーザーが認証されていないことを確認
        $this->assertGuest();
    }

    /**
     * 間違ったパスワードを入力すると、ログインができない
     */
    public function testUserCantLoginPassword(): void
    {
        // ログイン
        $response = $this->post(route('login'), [
            'email' => $this->user->email,
            'password' => 'ng_password',
        ]);

        // レスポンスの検証
        $response->assertStatus(302);

        // 指定したユーザーが認証されていないことを確認
        $this->assertGuest();
    }

    /**
     * ログアウトが可能
     */
    public function testCanLogout(): void
    {
        // ユーザを認証状態にする
        $this->actingAs($this->user);

        // ログアウトページへリクエストを送信
        $response = $this->post(route('logout'));

        // ログアウト後のレスポンスで、HTTPステータスコードが正常であることを確認
        $response->assertOk();

        // ユーザが認証されていないことを確認
        $this->assertGuest();
    }

    /**
     * 正しくメールアドレスとパスワード、パスワード再入力を入力すると、会員登録ができる
     */
    public function testUserCanRegister(): void
    {
        // 会員登録
        $response = $this->post(route('register'), [
            'email' => 'register@example.com',
            'name' => 'username',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        // レスポンスの検証
        $response->assertStatus(201);

        // データが登録されていることを確認
        $this->assertDatabaseHas('users', [
            'email' => 'register@example.com',
        ]);

        // ユーザーが認証されていることを確認
        $user = User::where('email', 'register@example.com')->first();
        $this->assertAuthenticatedAs($user);
    }

    /**
     * パスワードとパスワード再入力を違う値にすると、会員登録ができない
     */
    public function testUserCanRegisterPasswordConfirmation(): void
    {
        // 会員登録
        $response = $this->post(route('register'), [
            'email' => 'register@example.com',
            'name' => 'username',
            'password' => 'password',
            'password_confirmation' => 'NGpassword',
        ]);

        // レスポンスの検証
        $response->assertStatus(302);

        // データが登録されていないことを確認
        $this->assertDatabaseMissing('users', [
            'email' => 'register@example.com',
        ]);

        // ユーザーが認証されていないことを確認
        $this->assertGuest();
    }
}