<!--==========================================================================-->
<!-- パスワードリセット（送信）ページ -->
<!--==========================================================================-->
<template>
  <div class="c-container c-container--corp">
    <h1 class="c-title u-mb30">パスワードをお忘れの方</h1>

    <div v-if="!sendEmail">
      <div class="u-fs-s u-mb30 u-alC">
        ご登録のメールアドレス宛に再設定用のURLが送信されます。
      </div>

      <form @submit.prevent="submit">
        <!--==========================================-->
        <!-- メールアドレス -->
        <!--==========================================-->
        <form-group>
          <template #label>
            <vue-label :is-require="true" for-name="email">ご登録のメールアドレス</vue-label>
          </template>
          <vue-input id="email" v-model="form.email" name="email" />
          <input-error :errors="errors" name="email" />
        </form-group>

        <vue-button :loading="loading" text="送信" />
      </form>
    </div>

    <div v-else>
      <div class="u-fs-s u-mb30 u-alC">
        パスワード再設定用のメールが送信されました。
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'

export default {
  // =========================================================
  // タブに表示されるタイトル
  // =========================================================
  title: 'パスワードをお忘れの方',
  // =========================================================
  // データ
  // =========================================================
  data() {
    return {
      form: {
        email: '',
      },
      sendEmail: false,
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
     * パスワードリセットメール送信
     */
    async submit() {
      // ボタンをローディング状態にする
      this.loading = true
      try {
        // authストアのpasswordEmailアクションを呼び出す
        await this.$store.dispatch('auth/passwordEmail', this.form)

        // パスワードリセットメール送信に成功した場合
        if (this.apiStatus) {
          // 送信済メッセージを表示する
          this.sendEmail = true
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
