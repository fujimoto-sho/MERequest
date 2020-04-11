<!--==========================================================================-->
<!-- 会員登録ページ -->
<!--==========================================================================-->
<template>
  <div class="c-container c-container--corp">
    <h1 class="c-title u-mb30">会員登録</h1>

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

      <vue-button :loading="loading" text="登録" />
    </form>
  </div>
</template>

<script>
import { mapState } from 'vuex'

export default {
  // =========================================================
  // タブに表示されるタイトル
  // =========================================================
  title: '会員登録',
  // =========================================================
  // データ
  // =========================================================
  data() {
    return {
      form: {
        name: this.generateUserName(),
        email: '',
        password: '',
        password_confirmation: '',
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
     * ユーザー登録処理
     */
    async submit() {
      // ボタンをローディング状態にする
      this.loading = true
      try {
        // authストアのregisterアクションを呼び出す
        await this.$store.dispatch('auth/register', this.form)

        // ユーザー登録に成功した場合
        if (this.apiStatus) {
          // ユーザーのセット
          await this.$store.dispatch('auth/currentUser')
          // マイページに移動
          this.$router.push('/mypage')
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
    /**
     * ユーザ名の生成
     *
     * ユーザ名は、デフォルトで10文字のランダム文字列とする
     * ユーザ登録後、プロフィール編集でユーザ名を編集可能
     *
     * @returns {string}
     */
    generateUserName() {
      // 生成する文字列の長さ
      const length = 10

      // 生成する文字列に含める文字
      const char = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'
      const charCount = char.length

      // ランダム文字列を生成
      let name = ''
      for (let i = 0; i < length; i++) {
        name += char[Math.floor(Math.random() * charCount)]
      }

      return name
    },
  },
}
</script>
