<!--==========================================================================-->
<!-- マイページ -->
<!--==========================================================================-->
<template>
  <div class="c-container">
    <div v-if="displayUser" class="c-container c-container--corp u-mb30 u-pb20">
      <h1 class="c-title">{{ displayUser.name }}</h1>

      <div class="c-avatar c-avatar--l u-mt0auto u-mb20">
        <img :src="displayUser.avatar_url" alt="" class="c-avatar__image" />
      </div>

      <pre class="u-fs-s u-mb30 u-wb-all u-ws-pw">{{ displayUserBio }}</pre>
    </div>
    <loading-overlay v-else />

    <div v-if="postProposition">
      <div class="p-mypage-bar">
        <div>投稿した案件</div>
        <RouterLink to="/mypage/post/list" class="u-link-under">more>></RouterLink>
      </div>
      <proposition-cards :propositions="postProposition" @like="onLikeClick" id-prefix="post" />
    </div>
    <loading-overlay v-else />

    <div v-if="isLoginUserMypage">
      <div v-if="applicationProposition">
        <div class="p-mypage-bar">
          <div>応募した案件</div>
          <RouterLink to="/mypage/application/list" class="u-link-under">more>></RouterLink>
        </div>
        <proposition-cards :propositions="applicationProposition" @like="onLikeClick" id-prefix="application" />
      </div>
      <loading-overlay v-else />
    </div>

    <div v-if="isLoginUserMypage">
      <div v-if="messageProposition">
        <div class="p-mypage-bar">
          <div>パブリックメッセージを投稿した案件</div>
          <RouterLink to="/mypage/message/list" class="u-link-under">more>></RouterLink>
        </div>
        <proposition-cards :propositions="messageProposition" @like="onLikeClick" id-prefix="message" />
      </div>
      <loading-overlay v-else />
    </div>

    <div v-if="isLoginUserMypage">
      <div v-if="likeProposition">
        <div class="p-mypage-bar">
          <div>お気に入り登録した案件</div>
          <RouterLink to="/mypage/like/list" class="u-link-under">more>></RouterLink>
        </div>
        <proposition-cards :propositions="likeProposition" @like="onLikeClick" id-prefix="like" />
      </div>
      <loading-overlay v-else />
    </div>

    <div v-if="isLoginUserMypage">
      <div v-if="directMessageApplications">
        <div class="p-mypage-bar">
          <div>ダイレクトメッセージ</div>
          <RouterLink to="/direct_messages" class="u-link-under">more>></RouterLink>
        </div>
        <direct-message-cards :applications="directMessageApplications" />
      </div>
      <loading-overlay v-else />
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import { STATUS_OK } from '../../util'
import likeMixin from '../../mixins/like'

export default {
  // =========================================================
  // タブに表示されるタイトル
  // =========================================================
  title: 'マイページ',
  // =========================================================
  // ミックスイン
  // =========================================================
  mixins: [likeMixin],
  // =========================================================
  // routerから渡ってくる値
  // =========================================================
  props: {
    id: {
      type: String,
      default: null,
    },
  },
  // =========================================================
  // データ
  // =========================================================
  data() {
    return {
      displayUser: null,
      postProposition: null,
      applicationProposition: null,
      messageProposition: null,
      likeProposition: null,
      directMessageApplications: null,
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
      // ログインしているユーザー情報
      loginUser: state => state.auth.user,
    }),
    /**
     * urlにユーザーIDが含まれていない場合はそのID、
     * 以外はログインユーザーのIDを返却する
     *
     * @returns {number}
     */
    userId() {
      if (this.id !== null) {
        return this.id
      }
      return this.loginUser !== null ? this.loginUser.id : 0
    },
    /**
     * profileが存在したらbioを返却する
     *
     * そのまま表示するとエラーになるため、
     * プロフィールの存在判定行って、存在した場合のみbioを返却する
     * 存在しない場合は空白を返却する
     *
     * @returns {number}
     */
    displayUserBio() {
      return this.displayUser.profile !== null ? this.displayUser.profile.bio : ''
    },
    /**
     * ログインユーザーのマイページかの判定
     *
     * ログインユーザー = 表示しているユーザー：true
     * ログインユーザー ≠ 表示しているユーザー：false
     *
     * @returns {boolean}
     */
    isLoginUserMypage() {
      const loginUserId = this.loginUser !== null ? this.loginUser.id : null
      const displayUserId = this.displayUser !== null ? this.displayUser.id : null
      return loginUserId === displayUserId
    },
  },
  // =========================================================
  // 監視プロパティ
  // =========================================================
  watch: {
    $route: {
      async handler() {
        await this.fetchUser()
        await this.fetchPostProposition()

        // 自分のページを表示している場合のみ取得する情報
        if (this.isLoginUserMypage) {
          await this.fetchApplicationProposition()
          await this.fetchMessageProposition()
          await this.fetchLikeProposition()
          await this.fetchDirectMessageProposition()
        }
      },
      immediate: true,
    },
  },
  // =========================================================
  // メソッド
  // =========================================================
  methods: {
    /**
     * ユーザー情報を取得する
     *
     * axiosで通信を行い、返ってきたデータをセットする
     */
    async fetchUser() {
      const response = await axios.get(`/api/mypage/${this.userId}`)

      // OKステータス以外は、エラーコードをセットする
      if (response.status !== STATUS_OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      // 返ってきたデータをセットする
      this.displayUser = response.data
    },
    /**
     * 投稿した案件情報を取得する
     *
     * axiosで通信を行い、返ってきたデータをセットする
     */
    async fetchPostProposition() {
      const response = await axios.get(`/api/mypage/${this.userId}/fetch`, {
        params: {
          per_page: 2,
          is_post: 1,
        },
      })

      // OKステータス以外は、エラーコードをセットする
      if (response.status !== STATUS_OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      // 返ってきたデータをセットする
      this.postProposition = response.data.data
    },
    /**
     * 応募した案件情報を取得する
     *
     * axiosで通信を行い、返ってきたデータをセットする
     */
    async fetchApplicationProposition() {
      const response = await axios.get(`/api/mypage/${this.userId}/fetch`, {
        params: {
          per_page: 2,
          is_application: 1,
        },
      })

      // OKステータス以外は、エラーコードをセットする
      if (response.status !== STATUS_OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      // 返ってきたデータをセットする
      this.applicationProposition = response.data.data
    },
    /**
     * 投稿したパブリックメッセージ情報を取得する
     *
     * axiosで通信を行い、返ってきたデータをセットする
     */
    async fetchMessageProposition() {
      const response = await axios.get(`/api/mypage/${this.userId}/fetch`, {
        params: {
          per_page: 2,
          is_message: 1,
        },
      })

      // OKステータス以外は、エラーコードをセットする
      if (response.status !== STATUS_OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      // 返ってきたデータをセットする
      this.messageProposition = response.data.data
    },
    /**
     * お気に入り登録した案件情報を取得する
     *
     * axiosで通信を行い、返ってきたデータをセットする
     */
    async fetchLikeProposition() {
      const response = await axios.get(`/api/mypage/${this.userId}/fetch`, {
        params: {
          per_page: 2,
          is_like: 1,
        },
      })

      // OKステータス以外は、エラーコードをセットする
      if (response.status !== STATUS_OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      // 返ってきたデータをセットする
      this.likeProposition = response.data.data
    },
    /**
     * ダイレクトメッセージ情報を取得する
     *
     * axiosで通信を行い、返ってきたデータをセットする
     */
    async fetchDirectMessageProposition() {
      const response = await axios.get(`/api/direct_messages`, {
        params: {
          per_page: 2,
        },
      })

      // OKステータス以外は、エラーコードをセットする
      if (response.status !== STATUS_OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      // 返ってきたデータをセットする
      this.directMessageApplications = response.data.data
    },
    /**
     * お気に入りの登録をする
     *
     * axiosで通信を行い、お気に入りの登録を行う
     *
     * 通信後、表示している案件のお気に入り情報を変更する
     *
     * @param id 案件ID
     */
    async like(id) {
      const response = await axios.put(`/api/propositions/${id}/like`)

      // OKステータス以外は、エラーコードをセットする
      if (response.status !== STATUS_OK) {
        this.$store.commit('error/setCode', response.status)
        return
      }

      // 表示している案件のお気に入り情報を変更する
      this.postProposition = await this.setLikeInfo(this.postProposition, id, true)
      this.applicationProposition = await this.setLikeInfo(this.applicationProposition, id, true)
      this.messageProposition = await this.setLikeInfo(this.messageProposition, id, true)
      this.likeProposition = await this.setLikeInfo(this.likeProposition, id, true)
    },
    /**
     * お気に入りの削除をする
     *
     * axiosで通信を行い、お気に入りの削除を行う
     *
     * 通信後、表示している案件のお気に入り情報を変更する
     *
     * @param id 案件ID
     */
    async unlike(id) {
      const response = await axios.delete(`/api/propositions/${id}/like`)

      // OKステータス以外は、エラーコードをセットする
      if (response.status !== STATUS_OK) {
        this.$store.commit('error/setCode', response.status)
        return
      }

      // 表示している案件のお気に入り情報を変更する
      this.postProposition = await this.setLikeInfo(this.postProposition, id, false)
      this.applicationProposition = await this.setLikeInfo(this.applicationProposition, id, false)
      this.messageProposition = await this.setLikeInfo(this.messageProposition, id, false)
      this.likeProposition = await this.setLikeInfo(this.likeProposition, id, false)
    },
  },
}
</script>
