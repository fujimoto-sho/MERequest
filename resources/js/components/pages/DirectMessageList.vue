<!--==========================================================================-->
<!-- ダイレクトメッセージ一覧ページ -->
<!--==========================================================================-->
<template>
  <div class="c-container">
    <h1 class="c-title u-mb30">ダイレクトメッセージ一覧</h1>

    <!--======================================================================================-->
    <!-- 検索情報 -->
    <!--======================================================================================-->
    <section v-if="applications" class="u-mtb30 u-ml10">
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
      <div v-if="applications">
        <!-- 案件一覧 -->
        <direct-message-cards v-if="applications" :applications="applications" />
        <!-- ページネーション -->
        <pagination
          v-if="applications.length > 0"
          :current-page="currentPage"
          :last-page="lastPage"
          router-path="/direct_messages"
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
import pageCountMixin from '../../mixins/pageCount'

export default {
  // =========================================================
  // タブに表示されるタイトル
  // =========================================================
  title: 'ダイレクトメッセージ一覧',
  // =========================================================
  // コンポーネント
  // =========================================================
  components: {
    Pagination,
  },
  // =========================================================
  // ミックスイン
  // =========================================================
  mixins: [pageCountMixin],
  // =========================================================
  // データ
  // =========================================================
  data() {
    return {
      applications: null,
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
  },
  // =========================================================
  // 監視プロパティ
  // =========================================================
  watch: {
    $route: {
      async handler() {
        await this.fetchApplications()
      },
      immediate: true,
    },
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
    async fetchApplications() {
      this.applications = null

      const response = await axios.get(`/api/direct_messages`, {
        params: {
          page: this.$route.query.page,
          per_page: 10,
        },
      })

      // OKステータス以外は、エラーコードをセットする
      if (response.status !== STATUS_OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      // 返ってきたデータをセットする
      this.applications = response.data.data
      this.currentPage = response.data.current_page
      this.lastPage = response.data.last_page
      this.total = response.data.total
    },
  },
}
</script>
