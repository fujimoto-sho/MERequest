<!--==========================================================================-->
<!-- 案件詳細ページ -->
<!--==========================================================================-->
<template>
  <div v-if="proposition" class="c-container">
    <div class="c-container c-container--corp u-mb30">
      <h1 class="c-title u-mb30">{{ proposition.title }}</h1>

      <vue-button v-if="isPostUser" @click="moveEdit" text="編集" class="u-mb30" />

      <vue-button
        v-if="isLogin && !isPostUser && !proposition.is_application && !proposition.user.is_withdraw"
        @click="application"
        :loading="loading"
        text="応募する"
        class="u-mb30"
      />
      <vue-button
        v-if="isLogin && !isPostUser && proposition.is_application"
        @click="moveDirectMessage"
        text="ダイレクトメッセージ"
        class="u-mb30"
      />

      <figure class="c-avatar__link-wrap">
        <RouterLink :to="`/mypage?id=${proposition.user.id}`">
          <div class="c-avatar c-avatar--m u-mt0auto">
            <img :src="proposition.user.avatar_url" alt="アバター" class="c-avatar__image" />
          </div>
          <figcaption class="u-wb-all">{{ proposition.user.name }}</figcaption>
        </RouterLink>
      </figure>

      <div class="p-proposition-like__wrap">
        <div
          @click="likeClick"
          :class="{ 'p-proposition-like--active': proposition.is_like }"
          class="p-proposition-like"
        >
          <span class="typcn typcn-star-full-outline p-proposition-like__icon"></span>
          <span>{{ proposition.likes_count }}</span>
        </div>
      </div>

      <table class="p-proposition-table u-mb30">
        <tr>
          <th class="p-proposition-table__data p-proposition-table__data--head">募集状況</th>
          <td class="p-proposition-table__data">{{ proposition.status_name }}</td>
        </tr>
        <tr>
          <th class="p-proposition-table__data p-proposition-table__data--head">{{ numberTitle }}</th>
          <td class="p-proposition-table__data">
            {{ proposition.formatted_number_min }}〜{{ proposition.formatted_number_max }}
          </td>
        </tr>
        <tr>
          <th class="p-proposition-table__data p-proposition-table__data--head">掲載日</th>
          <td class="p-proposition-table__data">{{ proposition.publication_date }}</td>
        </tr>
        <tr>
          <th class="p-proposition-table__data p-proposition-table__data--head">募集人数</th>
          <td class="p-proposition-table__data">{{ proposition.recruiting_count }}人</td>
        </tr>
        <tr>
          <th class="p-proposition-table__data p-proposition-table__data--head">応募人数</th>
          <td class="p-proposition-table__data">{{ proposition.applications_count }}人</td>
        </tr>
      </table>

      <pre class="u-fs-s u-mb30 u-wb-all u-ws-pw">{{ proposition.content }}</pre>

      <div class="p-share">
        <h3 class="p-share__title">SHARE</h3>
        <ul class="p-share__icons">
          <li @click="shareTwitter" class="p-share__icon p-share__icon--twitter">
            <span class="typcn typcn-social-twitter"></span>
            <div class="u-fs-ss u-alC">ツイート</div>
          </li>
          <li @click="shareFacebook" class="p-share__icon p-share__icon--facebook">
            <span class="typcn typcn-social-facebook"></span>
            <div class="u-fs-ss u-alC">シェア</div>
          </li>
        </ul>
      </div>
    </div>

    <div v-if="(isLogin && proposition.status === 0) || hasMessages" class="c-container c-container--corp">
      <!--==========================================-->
      <!-- メッセージ -->
      <!--==========================================-->
      <messages v-if="proposition" :messages="messages" :proposition-user-id="proposition.user_id" />

      <!--==========================================-->
      <!-- コメント -->
      <!--==========================================-->
      <form v-if="isLogin && proposition.status === 0" @submit.prevent="addMessage">
        <form-group>
          <template #label>
            <vue-label :is-require="false" for-name="message">コメント</vue-label>
          </template>
          <vue-textarea id="content" v-model="form.message" name="message" />
          <input-error :errors="errors" name="message" />
        </form-group>

        <vue-button :loading="loading" text="送信" />
      </form>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import { STATUS_CREATED, STATUS_OK, STATUS_UNPROCESSABLE_ENTITY } from '../../util'

export default {
  // =========================================================
  // routerから渡ってくる値
  // =========================================================
  props: {
    id: {
      type: String,
      required: true,
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
      proposition: null,
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
      // ユーザー情報
      user: state => state.auth.user,
    }),
    /**
     * ログイン判定
     *
     * ログインしている：true
     * ログインしていない：false
     *
     * @returns {boolean}
     */
    isLogin() {
      return this.$store.getters['auth/check']
    },
    /**
     * 表示している案件がログインユーザーが投稿したものか
     *
     * ログインしている、かつ ログインユーザーID = 案件投稿ユーザーID：true
     * 以外：false
     *
     * @returns {boolean}
     */
    isPostUser() {
      return this.isLogin ? this.user.id === this.proposition.user_id : false
    },
    /**
     * 案件に紐付いているパブリックメッセージを返却する
     *
     * 存在しない場合は空の配列を返却する
     *
     * @returns {array}
     */
    messages() {
      return this.proposition !== null ? this.proposition.messages : []
    },
    /**
     * パブリックメッセージの存在判定
     *
     * 存在する：true
     * 存在しない：false
     *
     * @returns {boolean}
     */
    hasMessages() {
      return this.messages.length > 0
    },
    /**
     * 数値のタイトル
     *
     * @returns {string}
     */
    numberTitle() {
      return this.proposition.type === 0 ? '金額' : '配分率'
    },
  },
  // =========================================================
  // 監視プロパティ
  // =========================================================
  watch: {
    $route: {
      async handler() {
        await this.fetchProposition()
      },
      immediate: true,
    },
  },
  // =========================================================
  // メソッド
  // =========================================================
  methods: {
    /**
     * 案件情報の取得
     *
     * axiosで通信を行い、返ってきたデータをセットする
     */
    async fetchProposition() {
      const response = await axios.get(`/api/propositions/${this.id}`)

      // OKステータス以外は、エラーコードをセットする
      if (response.status !== STATUS_OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      // 返ってきたデータをセットする
      this.proposition = response.data

      // タブのタイトルを案件名にする
      document.title = `${this.proposition.title} - MERequest`
    },
    /**
     * パブリックメッセージの投稿
     *
     * axiosで通信を行い、メッセージの投稿を行う
     *
     * 通信後、投稿したメッセージが返ってくるので、そのメッセージを一覧に追加する
     */
    async addMessage() {
      // ボタンをローディング状態にする
      this.loading = true
      try {
        const response = await axios.post(`/api/messages/${this.id}`, this.form)

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
        this.errors = null
        this.form.message = ''

        // 投稿されたメッセージを、メッセージ一覧に追加する
        this.$set(this.proposition, 'messages', [...this.proposition.messages, response.data])
      } finally {
        // ボタンのローディングを解除する
        this.loading = false
      }
    },
    /**
     * ダイレクトメッセージ画面に遷移する
     */
    moveDirectMessage() {
      this.$router.push(`/direct_messages/${this.proposition.applications[0].id}`)
    },
    /**
     * 応募の登録処理
     */
    async application() {
      // ボタンをローディング状態にする
      this.loading = true
      try {
        const response = await axios.post(`/api/propositions/${this.proposition.id}/application`)

        // OKステータス以外は、エラーコードをセットする
        if (response.status !== STATUS_OK) {
          this.$store.commit('error/setCode', response.status)
          return false
        }

        // 案件情報の再取得
        await this.fetchProposition()

        // フラッシュメッセージを表示する
        this.$store.commit('global/setFlashMessage', {
          message: '応募が完了しました。',
        })

        // ダイレクトメッセージに遷移
        this.moveDirectMessage()
      } finally {
        // ボタンのローディングを解除する
        this.loading = false
      }
    },
    /**
     * お気に入りの 登録/削除 判定
     */
    likeClick() {
      // ログインしていなかったらなにもしない
      if (!this.$store.getters['auth/check']) {
        return false
      }

      if (this.proposition.is_like) {
        // お気に入り削除
        this.unlike()
      } else {
        // お気に入り登録
        this.like()
      }
    },
    /**
     * お気に入りの登録をする
     *
     * axiosで通信を行い、お気に入りの登録を行う
     *
     * 通信後、表示している案件のお気に入り情報を変更する
     */
    async like() {
      const response = await axios.put(`/api/propositions/${this.proposition.id}/like`)

      // OKステータス以外は、エラーコードをセットする
      if (response.status !== STATUS_OK) {
        this.$store.commit('error/setCode', response.status)
        return
      }

      // お気に入り数を変更
      this.$set(this.proposition, 'likes_count', this.proposition.likes_count + 1)
      // 自分がお気に入りしているかの情報を変更
      this.$set(this.proposition, 'is_like', true)
    },
    /**
     * お気に入りの削除をする
     *
     * axiosで通信を行い、お気に入りの削除を行う
     *
     * 通信後、表示している案件のお気に入り情報を変更する
     */
    async unlike() {
      const response = await axios.delete(`/api/propositions/${this.proposition.id}/like`)

      // OKステータス以外は、エラーコードをセットする
      if (response.status !== STATUS_OK) {
        this.$store.commit('error/setCode', response.status)
        return
      }

      // お気に入り数を変更
      this.$set(this.proposition, 'likes_count', this.proposition.likes_count - 1)
      // 自分がお気に入りしているかの情報を変更
      this.$set(this.proposition, 'is_like', false)
    },
    /**
     * 案件をtwitterでシェアする
     */
    shareTwitter() {
      const text = `${this.proposition.title}【MERequest】`
      const fullUrl = encodeURIComponent(location.href)
      const url = `https://twitter.com/intent/tweet?text=${text}&url=${fullUrl}`
      const option = 'status=1,width=818,height=400,top=100,left=100'
      window.open(url, 'twitter', option)
    },
    /**
     * 案件をfacebookでシェアする
     */
    shareFacebook() {
      const fullUrl = encodeURIComponent(location.href)
      const url = `https://www.facebook.com/sharer/sharer.php?u=${fullUrl}`
      window.open(url, 'facebook')
    },
    /**
     * 編集画面に遷移する
     */
    moveEdit() {
      this.$router.push(`/propositions/${this.id}/edit`)
    },
  },
}
</script>
