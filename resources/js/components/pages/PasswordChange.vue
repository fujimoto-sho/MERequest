<!--==========================================================================-->
<!-- パスワード変更ページ -->
<!--==========================================================================-->
<template>
  <div class="c-container c-container--corp">
    <h1 class="c-title u-mb30">パスワード変更</h1>

    <form @submit.prevent="submit">
      <!--==========================================-->
      <!-- 現在のパスワード -->
      <!--==========================================-->
      <form-group>
        <template #label>
          <vue-label :is-require="true" for-name="old_password">現在のパスワード</vue-label>
        </template>
        <vue-input id="old_password" v-model="form.old_password" type="password" name="old_password" />
        <input-error :errors="errors" name="old_password" />
      </form-group>

      <!--==========================================-->
      <!-- 新しいパスワード -->
      <!--==========================================-->
      <form-group>
        <template #label>
          <vue-label :is-require="true" for-name="password">新しいパスワード</vue-label>
        </template>
        <vue-input id="password" v-model="form.password" type="password" name="password" placeholder="8文字以上" />
        <input-error :errors="errors" name="password" />
      </form-group>

      <!--==========================================-->
      <!-- 新しいパスワード（確認） -->
      <!--==========================================-->
      <form-group>
        <template #label>
          <vue-label :is-require="true" for-name="password_confirmation">新しいパスワード（確認）</vue-label>
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
import { STATUS_OK, STATUS_UNPROCESSABLE_ENTITY } from '../../util'

export default {
  // =========================================================
  // タブに表示されるタイトル
  // =========================================================
  title: 'パスワード変更',
  // =========================================================
  // データ
  // =========================================================
  data() {
    return {
      form: {
        old_password: '',
        password: '',
        password_confirmation: '',
        token: this.$route.params.token,
      },
      errors: null,
      loading: false,
    }
  },
  // =========================================================
  // ライフサイクルフック created
  // データの監視とイベントの初期セットアップが完了した状態で呼ばれる
  // =========================================================
  created() {
    // エラーのクリア
    this.clearError()
  },
  // =========================================================
  // メソッド
  // =========================================================
  methods: {
    /**
     * パスワード変更処理
     *
     * axiosで通信を行い、パスワード変更を行う
     *
     * 通信が成功したらマイページに遷移する
     */
    async submit() {
      // ボタンをローディング状態にする
      this.loading = true
      try {
        const response = await axios.put('/api/password/change', this.form)

        // バリデーションエラーの場合は、そのメッセージをセットする
        if (response.status === STATUS_UNPROCESSABLE_ENTITY) {
          this.errors = response.data.errors
          return false
        }

        // バリデーション以外のエラーは、エラーコードをセットする
        if (response.status !== STATUS_OK) {
          this.$store.commit('error/setCode', response.status)
          return false
        }

        // フラッシュメッセージを表示する
        this.$store.commit('global/setFlashMessage', {
          message: 'パスワードを変更しました。',
        })

        // マイページに遷移
        this.$router.push('/mypage')
      } finally {
        // ボタンのローディングを解除する
        this.loading = false
      }
    },
    /**
     * フォームエラーメッセージのクリア
     */
    clearError() {
      this.errors = null
    },
  },
}
</script>
