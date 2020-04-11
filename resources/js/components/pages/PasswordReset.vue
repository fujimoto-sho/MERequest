<!--==========================================================================-->
<!-- パスワードリセット（変更）ページ -->
<!--==========================================================================-->
<template>
  <div class="c-container c-container--corp">
    <h1 class="c-title u-mb30">パスワード再設定</h1>

    <form @submit.prevent="submit">
      <!--==========================================-->
      <!-- メールアドレス -->
      <!--==========================================-->
      <form-group>
        <template #label>
          <vue-label :is-require="true" for-name="email">メールアドレス</vue-label>
        </template>
        <vue-input id="email" v-model="form.email" name="email" />
        <input-error :errors="errors" name="email" />
      </form-group>

      <!--==========================================-->
      <!-- パスワード -->
      <!--==========================================-->
      <form-group>
        <template #label>
          <vue-label :is-require="true" for-name="password">パスワード</vue-label>
        </template>
        <vue-input id="password" v-model="form.password" type="password" name="password" placeholder="8文字以上" />
        <input-error :errors="errors" name="password" />
      </form-group>

      <!--==========================================-->
      <!-- パスワード（確認） -->
      <!--==========================================-->
      <form-group>
        <template #label>
          <vue-label :is-require="true" for-name="password_confirmation">パスワード（確認）</vue-label>
        </template>
        <vue-input
          id="password_confirmation"
          v-model="form.password_confirmation"
          type="password"
          name="password_confirmation"
          placeholder="8文字以上"
        />
        <input-error :errors="errors" name="password_confirmation" />
      </form-group>

      <vue-button :loading="loading" text="変更" />
    </form>
  </div>
</template>

<script>
import { mapState } from 'vuex'

export default {
  // =========================================================
  // タブに表示されるタイトル
  // =========================================================
  title: 'パスワード再設定',
  // =========================================================
  // データ
  // =========================================================
  data() {
    return {
      form: {
        email: '',
        password: '',
        password_confirmation: '',
        token: this.$route.params.token,
      },
      loading: false,
    }
  },
  // =========================================================
  // 算出プロパティ
  // =========================================================
  computed: {
    /**
     * ストアからstateを取得する
     */
    ...mapState({
      // ステータスが正常値か
      apiStatus: state => state.auth.apiStatus,
      // フォームのエラー情報
      errors: state => state.auth.errorMessages,
    }),
  },
  // =========================================================
  // ライフサイクルフック created
  // データの監視とイベントの初期セットアップが完了した状態で呼ばれる
  // =========================================================
  created() {
    this.clearError()
  },
  // =========================================================
  // メソッド
  // =========================================================
  methods: {
    /**
     * パスワードリセット処理
     */
    async submit() {
      // ボタンをローディング状態にする
      this.loading = true
      try {
        // authストアのpasswordResetアクションを呼び出す
        await this.$store.dispatch('auth/passwordReset', this.form)

        // パスワードリセットに成功した場合
        if (this.apiStatus) {
          // フラッシュメッセージを表示する
          this.$store.commit('global/setFlashMessage', {
            message: 'パスワードを変更しました',
          })

          // ログインページに移動
          this.$router.push('/Login')
        }
      } finally {
        // ボタンのローディングを解除する
        this.loading = false
      }
    },
    /**
     * フォームエラーメッセージのクリア
     */
    clearError() {
      this.$store.commit('auth/setErrorMessages', null)
    },
  },
}
</script>
