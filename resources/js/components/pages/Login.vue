<!--==========================================================================-->
<!-- ログインページ -->
<!--==========================================================================-->
<template>
  <div class="c-container c-container--corp">
    <h1 class="c-title u-mb30">ログイン</h1>

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
        <vue-input id="password" v-model="form.password" type="password" name="password" />
        <input-error :errors="errors" name="password" />
      </form-group>

      <!--==========================================-->
      <!-- ログイン保持 -->
      <!--==========================================-->
      <div class="p-remember u-mb30">
        <input id="remember_me" type="checkbox" name="remember_me" class="p-remember__check" />
        <label for="remember_me" class="p-remember__label">ログインを保存する</label>
      </div>

      <vue-button :loading="loading" text="ログイン" />

      <div class="u-fs-s u-mt30 u-alC">
        パスワードをお忘れの方は<router-link class="u-link-under" to="/password/email">こちら</router-link>
      </div>
      <div class="u-fs-s u-mt30 u-alC">
        テストメールアドレス：example@example.com
      </div>
      <div class="u-fs-s u-alC">
        テストパスワード：testtest
      </div>
    </form>
  </div>
</template>

<script>
import { mapState } from 'vuex'

export default {
  // =========================================================
  // タブに表示されるタイトル
  // =========================================================
  title: 'ログイン',
  // =========================================================
  // データ
  // =========================================================
  data() {
    return {
      form: {
        email: '',
        password: '',
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
  // 監視プロパティ
  // =========================================================
  watch: {
    $route: {
      async handler() {
        // トークンのリフレッシュ
        await this.refreshToken()
      },
      immediate: true,
    },
  },
  // =========================================================
  // ライフサイクルフック created
  // データの監視とイベントの初期セットアップが完了した状態で呼ばれる
  // =========================================================
  async created() {
    // エラーのクリア
    this.clearError()
  },
  // =========================================================
  // メソッド
  // =========================================================
  methods: {
    /**
     * ログイン処理
     */
    async submit() {
      // ボタンをローディング状態にする
      this.loading = true
      try {
        // authストアのloginアクションを呼び出す
        await this.$store.dispatch('auth/login', this.form)

        // ログインに成功した場合
        if (this.apiStatus) {
          // ユーザーのセット
          await this.$store.dispatch('auth/currentUser')
          // トークンのリフレッシュ
          await this.refreshToken()
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
     * トークンのリフレッシュ
     *
     * axiosで通信を行い、再生成されたcsrfトークンを、axiosのヘッダーにセットする
     */
    async refreshToken() {
      const response = await axios.get('/api/refresh-token')
      // 再生成されたcsrfトークンを、axiosのヘッダーにセットする
      window.axios.defaults.headers.common['X-CSRF-TOKEN'] = response.data
    },
  },
}
</script>
