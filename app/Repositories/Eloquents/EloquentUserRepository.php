<?php

namespace App\Repositories\Eloquents;

use App\Models\Profile;
use App\Models\User;
use App\Repositories\Contracts\UserContract;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * リポジトリパターン Eloquent ユーザー
 *
 * インターフェイスをEloquentで実装する
 *
 * @package App\Repositories\Eloquents
 */
class EloquentUserRepository implements UserContract
{
    /**
     * マイページに表示するユーザー情報を返却する
     *
     * @param int $id ユーザーID
     * @return mixed
     */
    public function mypage(int $id)
    {
        return User::where('id', $id)->with(['profile'])->first();
    }

    /**
     * パスワードを変更する
     *
     * @param string $password パスワード
     * @return bool
     * @throws \Exception
     */
    public function passwordChange(string $password)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $user->password = bcrypt($password);
            $user->save();

            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception);

            throw new \Exception('パスワードの変更に失敗しました', 500);
        }
    }

    /**
     * 退会する
     *
     * ユーザーテーブル自体は削除しない
     * 退会ユーザーテーブルに追加し、ログインをできなくする
     *
     * プロフィール、お気に入りは削除する
     *
     * @throws \Exception
     */
    public function withdraw()
    {
        $now = Carbon::now();
        $user = Auth::user();

        DB::beginTransaction();
        try {
            // 退会ユーザーテーブルにメールアドレスをセット
            DB::table('withdraw_users')->insert([
                'email'      => $user->email,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // プロフィールを削除
            if ($user->profile()->exists()) {
                $user->profile->delete();
            }

            // お気に入りを削除
            $user->likes()->detach();

            // ユーザ名の最初に（退会）をつける
            $user->name = '（退会）' . $user->name;
            $user->save();

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception);

            throw new \Exception('ユーザー情報の削除に失敗しました', 500);
        }
    }

    /**
     * ユーザー情報を更新する
     *
     * @param array $params パラメータ
     * @param int $id ユーザーID
     * @throws \Exception
     */
    public function update(array $params, int $id)
    {
        DB::beginTransaction();
        try {
            // ユーザーテーブル更新
            $user = User::find($id);
            $user->fill($params)->save();

            // プロフィールテーブルにデータが存在していたら登録、以外は更新
            $profile = Profile::firstOrNew(['user_id' => $id]);
            $profile->bio = $params['bio'] ?? null;
            if (isset($params['image'])) {
                $profile->image = $params['image'];
            }
            $profile->save();

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception);

            // DB登録に失敗した場合、画像を削除する
            if (isset($params['image'])) {
                Storage::cloud()->delete($params['image']);
            }

            throw new \Exception('ユーザー情報の更新に失敗しました', 500);
        }
    }

    /**
     * ログインユーザー情報を返却する
     *
     * @return mixed
     */
    public function loginUser()
    {
        return User::with(['profile'])
            ->where('id', Auth::user()->id)
            ->first();
    }
}
