<!--==========================================================================-->
<!-- マイページから遷移できる案件一覧 -->
<!--==========================================================================-->
<template>
  <div class="c-container">
    <h1 class="c-title u-mb30">{{ title }}</h1>

    <!--======================================================================================-->
    <!-- 検索情報 -->
    <!--======================================================================================-->
    <section v-if="propositions" class="u-mtb30 u-ml10">
      <div class="u-fs-m u-fw-bold">検索結果</div>
      <div>
        <span class="u-fs-m u-fw-bold u-color-primary">{{ total }}</span>
        <span class="u-fs-m u-fw-bold">件</span>
        <span class="u-fs-s u-ml10">{{ displayMinCount }} - {{ displayMaxCount }}件を表示</span>
      </div>
    </section>

    <!--======================================================================================-->
    <!-- 検索結果 -->
    <!--======================================================================================-->
    <section class="u-mtb30">
      <div v-if="propositions">
        <!-- 案件一覧 -->
        <proposition-cards :propositions="propositions" @like="onLikeClick" />
        <!-- ページネーション -->
        <pagination
          v-if="propositions.length > 0"
          :current-page="currentPage"
          :last-page="lastPage"
          :router-path="`/mypage/${type}/list`"
        />
      </div>
      <loading-overlay v-else />
    </section>
  </div>
</template>

<script>
import { STATUS_OK } from '../../util'
import Pagination from '../molecules/Pagination.vue'
import { mapState } from 'vuex'
import likeMixin from '../../mixins/like'
import pageCountMixin from '../../mixins/pageCount'

export default {
  // =========================================================
  // タブに表示されるタイトル
  // =========================================================
  title() {
    return this.title
  },
  // =========================================================
  // コンポーネント
  // =========================================================
  components: {
    Pagination,
  },
  // =========================================================
  // ミックスイン
  // =========================================================
  mixins: [likeMixin, pageCountMixin],
  // =========================================================
  // routerから渡ってくる値
  // =========================================================
  props: {
    type: {
      type: String,
      required: true,
    },
  },
  // =========================================================
  // データ
  // =========================================================
  data() {
    return {
      propositions: null,
      currentPage: 0,
      lastPage: 0,
      total: 0,
      preview: null,
      loading: false,
      active: false,
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
      user: state => state.auth.user,
    }),
    /**
     * ページタイトル
     *
     * 表示する内容によってタイトルを変更する
     *
     * @returns {string}
     */
    title() {
      switch (this.type) {
        case 'post':
          return '投稿した案件一覧'
        case 'application':
          return '応募した案件一覧'
        case 'message':
          return 'パブリックメッセージを投稿した案件一覧'
        case 'like':
          return 'お気に入り登録した案件一覧'
        default:
          return ''
      }
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
  // プロパティ
  // =========================================================
  methods: {
    /**
     * 案件情報の取得
     *
     * axiosで通信を行い、返ってきたデータをセットする
     */
    async fetchProposition() {
      this.propositions = null
      const response = await axios.get(`/api/mypage/${this.user.id}/fetch`, {
        params: {
          page: this.$route.query.page,
          per_page: 10,
          is_post: this.type === 'post' ? 1 : null,
          is_application: this.type === 'application' ? 1 : null,
          is_message: this.type === 'message' ? 1 : null,
          is_like: this.type === 'like' ? 1 : null,
        },
      })

      // OKステータス以外は、エラーコードをセットする
      if (response.status !== STATUS_OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      // 返ってきたデータをセットする
      this.propositions = response.data.data
      this.currentPage = response.data.current_page
      this.lastPage = response.data.last_page
      this.total = response.data.total
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
      }

      // 表示している案件のお気に入り情報を変更する
      this.propositions = await this.setLikeInfo(this.propositions, id, true)
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
      }

      // 表示している案件のお気に入り情報を変更する
      this.propositions = await this.setLikeInfo(this.propositions, id, false)
    },
  },
}
</script>
