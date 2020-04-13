<!--==========================================================================-->
<!-- ダイレクトメッセージ詳細ページ -->
<!--==========================================================================-->
<template>
  <div v-if="application" class="c-container c-container--corp">
    <h1 class="c-title u-mb30">ダイレクトメッセージ</h1>
    <h2 class="u-fs-m u-fw-bold u-mb5 u-alC">案件名</h2>
    <p class="u-mb30 u-alC u-wb-all">
      <RouterLink :to="`/propositions/${application.proposition.id}`">
        {{ application.proposition.title }}
      </RouterLink>
    </p>
    <h2 class="u-fs-m u-fw-bold u-mb5 u-alC">相手ユーザ名</h2>
    <p class="u-mb30 u-alC u-wb-all">
      <RouterLink :to="`/mypage?id=${opponentUser.id}`">{{ opponentUser.name }}</RouterLink>
    </p>

    <!--==========================================-->
    <!-- メッセージ -->
    <!--==========================================-->
    <messages
      v-if="application"
      :messages="application.direct_messages"
      :proposition-user-id="application.proposition.user_id"
    />

    <!--==========================================-->
    <!-- コメント -->
    <!--==========================================-->
    <form v-if="!opponentUser.is_withdraw" @submit.prevent="addDirectMessage">
      <form-group>
        <template #label>
          <vue-label :is-require="false" for-name="message">コメント</vue-label>
        </template>
        <vue-textarea id="message" v-model="form.message" name="message" />
        <input-error :errors="errors" name="message" />
      </form-group>

      <vue-button :loading="loading" text="送信" />
    </form>
  </div>
</template>

<script>
import { STATUS_CREATED, STATUS_OK, STATUS_UNPROCESSABLE_ENTITY } from '../../util'
import { mapState } from 'vuex'

export default {
  // =========================================================
  // タブに表示されるタイトル
  // =========================================================
  title: 'ダイレクトメッセージ',
  // =========================================================
  // routerから渡ってくる値
  // =========================================================
  props: {
    isStart: { type: Boolean },
    applicationId: {
      type: String,
      default: '0',
    },
  },
  // =========================================================
  // データ
  // =========================================================
  data() {
    return {
      form: {
        message: '',
        token: this.$route.params.token,
      },
      application: null,
      errors: null,
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
      // ログインユーザー情報
      loginUser: state => state.auth.user,
    }),
    /**
     * 相手ユーザーを返却する
     *
     * ログインユーザーのid = 応募者のid：案件投稿ユーザー
     * ログインユーザーのid ≠ 応募者のid：案件応募ユーザー
     *
     * @return {object}
     */
    opponentUser() {
      return this.application.user.id === this.loginUser.id ? this.application.proposition.user : this.application.user
    },
  },
  // =========================================================
  // 監視プロパティ
  // =========================================================
  watch: {
    $route: {
      async handler() {
        await this.fetchApplication()
      },
      immediate: true,
    },
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
     * 応募情報を取得する
     *
     * axiosで通信を行い、返ってきたデータをセットする
     */
    async fetchApplication() {
      const response = await axios.get(`/api/direct_messages/${this.applicationId}`)

      // OKステータス以外は、エラーコードをセットする
      if (response.status !== STATUS_OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      // 返ってきたデータをセットする
      this.application = response.data
    },
    /**
     * ダイレクトメッセージの投稿
     *
     * axiosで通信を行い、メッセージの投稿を行う
     *
     * 通信後、投稿したメッセージが返ってくるので、そのメッセージを一覧に追加する
     */
    async addDirectMessage() {
      // ボタンをローディング状態にする
      this.loading = true
      try {
        const response = await axios.post(`/api/direct_messages/${this.applicationId}`, this.form)

        // バリデーションエラーの場合は、そのメッセージをセットする
        if (response.status === STATUS_UNPROCESSABLE_ENTITY) {
          this.errors = response.data.errors
          return false
        }

        // バリデーション以外のエラーは、エラーコードをセットする
        if (response.status !== STATUS_CREATED) {
          this.$store.commit('error/setCode', response.status)
          return false
        }

        // フラッシュメッセージを表示する
        this.$store.commit('global/setFlashMessage', {
          message: 'メッセージを送信しました',
        })

        // エラー・メッセージフォームをクリアする
        this.clearError()
        this.form.message = ''

        // 投稿されたメッセージを、メッセージ一覧に追加する
        this.$set(this.application, 'direct_messages', [...this.application.direct_messages, response.data])
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
