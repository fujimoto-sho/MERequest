<!--==========================================================================-->
<!-- 退会ページ -->
<!--==========================================================================-->
<template>
  <div class="c-container c-container--corp">
    <h1 class="c-title u-mb30">退会</h1>

    <div v-if="!completeWithdraw">
      <form @submit.prevent="submit">
        <div class="u-fs-m u-mb15 u-alC">注意事項</div>
        <div class="u-fs-s u-mb5 u-alC">・退会を取り消しすることはできません。</div>
        <div class="u-fs-s u-mb5 u-alC">・投稿した案件に、募集中のものがある場合は退会できません。</div>
        <div class="u-fs-s u-mb30 u-alC">
          ・知的保護の観点から、退会後もユーザ名、案件、応募情報を公開しております。
        </div>

        <div v-if="errorMessage" class="u-alC u-mb10">
          <div class="c-input-err">{{ errorMessage }}</div>
        </div>

        <vue-button :loading="loading" text="退会" />
      </form>
    </div>

    <div v-else>
      <div class="u-fs-m u-mb15 u-alC">退会が完了しました</div>
    </div>
  </div>
</template>

<script>
import { STATUS_OK, STATUS_UNPROCESSABLE_ENTITY } from '../../util'

export default {
  // =========================================================
  // タブに表示されるタイトル
  // =========================================================
  title: '退会',
  // =========================================================
  // データ
  // =========================================================
  data() {
    return {
      completeWithdraw: false,
      loading: false,
      errorMessage: null,
    }
  },
  // =========================================================
  // メソッド
  // =========================================================
  methods: {
    /**
     * 退会処理
     */
    async submit() {
      // ボタンをローディング状態にする
      this.loading = true
      try {
        const response = await axios.delete('/api/withdraw')

        // バリデーションエラーの場合は、そのメッセージをセットする
        if (response.status === STATUS_UNPROCESSABLE_ENTITY) {
          this.errorMessage = response.data.message
          return false
        }

        // バリデーション以外のエラーは、エラーコードをセットする
        if (response.status !== STATUS_OK) {
          this.$store.commit('error/setCode', response.status)
          return false
        }

        // ユーザーのセット
        this.$store.commit('auth/setUser', null)
        // 退会完了メッセージを表示する
        this.completeWithdraw = true
      } finally {
        // ボタンのローディングを解除する
        this.loading = false
      }
    },
  },
}
</script>
