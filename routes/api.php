<?php

//================================================================================
// CSRFトークンのリフレッシュ
//================================================================================
Route::get('/refresh-token','UserController@refreshToken')->name('refreshToken');

//================================================================================
// ユーザー認証が必要ないルーティング
//================================================================================
// 会員登録
Route::post('/register', 'Auth\RegisterController@register')->name('register');
// ログイン
Route::post('/login', 'Auth\LoginController@login')->name('login');

// パスワード再設定（送信）
Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
// パスワード再設定（リセット）
Route::post('/password/reset', 'Auth\ResetPasswordController@reset')->name('password.reset');

// マイページ
Route::get('/mypage/{id}', 'UserController@mypage')->name('mypage');
// マイページから飛べる案件一覧
Route::get('/mypage/{user_id}/fetch ', 'PropositionController@fetchMypageProposition')->name('mypage.fetch');

// 案件一覧
Route::get('/propositions', 'PropositionController@index')->name('propositions.index');
// 案件詳細
Route::get('/propositions/{proposition_id}', 'PropositionController@show')->name('propositions.show');

//================================================================================
// ユーザー認証が必要なルーティング
//================================================================================
Route::middleware(['auth'])->group(function () {
    // ログアウト
    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
    // パスワード変更
    Route::put('/password/change', 'UserController@passwordChange')->name('password.change');
    // 退会
    Route::delete('/withdraw', 'UserController@withdraw')->name('withdraw');

    // 認証ユーザー取得
    Route::get('/user', 'UserController@user')->name('user');
    // プロフィール編集
    Route::post('/user/update', 'UserController@update')->name('user.update');

    // 案件登録
    Route::post('/propositions', 'PropositionController@store')->name('propositions.store');
    // 案件編集（取得）
    Route::get('/propositions/{proposition_id}/edit', 'PropositionController@edit')->name('propositions.edit');
    // 案件編集（送信）
    Route::put('/propositions/{proposition_id}', 'PropositionController@update')->name('propositions.update');

    // 案件応募（取り消しは不可）
    Route::post('/propositions/{proposition_id}/application', 'PropositionController@application')->name('propositions.application');

    // パブリックメッセージ送信
    Route::post('/messages/{proposition_id}', 'MessageController@store')->name('messages.store');

    // ダイレクトメッセージ一覧
    Route::get('/direct_messages', 'DirectMessageController@index')->name('direct_messages');
    // ダイレクトメッセージ送信
    Route::post('/direct_messages/{application_id}', 'DirectMessageController@store')->name('direct_messages.store');
    // ダイレクトメッセージ詳細
    Route::get('/direct_messages/{application_id}', 'DirectMessageController@show')->name('direct_messages.show');

    // お気に入り追加
    Route::put('/propositions/{proposition_id}/like', 'PropositionController@like')->name('propositions.like');
    // お気に入り削除
    Route::delete('/propositions/{proposition_id}/like', 'PropositionController@unlike')->name('propositions.unlike');
});
