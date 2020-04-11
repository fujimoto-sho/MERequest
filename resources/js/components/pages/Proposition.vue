<!--==========================================================================-->
<!-- 案件一覧（検索）ページ -->
<!--==========================================================================-->
<template>
  <div class="c-container">
    <!--======================================================================================-->
    <!-- 検索ボックス -->
    <!--======================================================================================-->
    <section>
      <div @click="toggle" class="p-search-toggle">
        検索条件
        <span
          :class="{
            'typcn-arrow-sorted-down': !active,
            'typcn-arrow-sorted-up': active,
          }"
          class="typcn u-fs-l"
        ></span>
      </div>
      <slide-up-down :active="active">
        <div class="c-container--corp">
          <form @submit.prevent="submit">
            <!--==========================================-->
            <!-- フリーワード -->
            <!--==========================================-->
            <form-group>
              <template #label>
                <vue-label :is-require="false" for-name="free_word">フリーワード</vue-label>
              </template>
              <vue-input id="free_word" v-model="form.free_word" name="free_word" />
            </form-group>

            <!--==========================================-->
            <!-- 募集状況 -->
            <!--==========================================-->
            <form-group>
              <template #label>
                <vue-label :is-require="false" for-name="status">募集状況</vue-label>
              </template>
              <select id="status" v-model="form.status" name="status" class="c-input c-input--select">
                <option value="">全て</option>
                <option value="0">募集中</option>
                <option value="1">募集終了</option>
              </select>
            </form-group>

            <!--==========================================-->
            <!-- 金額 -->
            <!--==========================================-->
            <form-group v-if="isDisplaySingleBetween">
              <template #label>
                <vue-label :is-require="false" for-name="single_number_min">金額</vue-label>
              </template>
              <vue-input
                id="single_number_min"
                v-model="form.single_number_min"
                type="number"
                name="single_number_min"
                class="c-input--between"
              />
              <span class="c-input__tilda">〜</span>
              <vue-input
                id="single_number_max"
                v-model="form.single_number_max"
                type="number"
                name="single_number_max"
                class="c-input--between"
              />
              <span class="c-input__unit">円</span>
            </form-group>

            <!--==========================================-->
            <!-- 並び順 -->
            <!--==========================================-->
            <form-group>
              <template #label>
                <vue-label :is-require="false" for-name="order">並び順</vue-label>
              </template>
              <select id="order" v-model="form.order" name="order" class="c-input c-input--select">
                <option value="desc">新しい順</option>
                <option value="asc">古い順</option>
              </select>
            </form-group>

            <!--==========================================-->
            <!-- ボタン -->
            <!--==========================================-->
            <vue-button :loading="loading" text="検索" class="u-mb30" />
            <vue-button @click.prevent="reset" text="リセット" btn-type="ghost" />
          </form>
        </div>
      </slide-up-down>
    </section>

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
          router-path="/propositions"
        />
      </div>
      <loading-overlay v-else />
    </section>
  </div>
</template>

<script>
import Vue from 'vue'
import { STATUS_OK } from '../../util'
import SlideUpDown from 'vue-slide-up-down'
import Pagination from '../molecules/Pagination.vue'
import likeMixin from '../../mixins/like'
import pageCountMixin from '../../mixins/pageCount'

Vue.component('slide-up-down', SlideUpDown)

export default {
  // =========================================================
  // タブに表示されるタイトル
  // =========================================================
  title: '案件一覧',
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
  // データ
  // =========================================================
  data() {
    return {
      form: {
        free_word: '',
        status: '0',
        type: '',
        single_number_min: '',
        single_number_max: '',
        revenue_number_min: '',
        revenue_number_max: '',
        order: 'desc',
      },
      search: {
        free_word: '',
        status: '0',
        type: '',
        single_number_min: '',
        single_number_max: '',
        revenue_number_min: '',
        revenue_number_max: '',
        order: 'desc',
      },
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
     * 単発案件の金額検索を表示するか
     *
     * 選択されている案件種別が単発、または全ての場合に表示する
     *
     * @return {boolean}
     */
    isDisplaySingleBetween() {
      return this.form.type === '0' || this.form.type === ''
    },
    /**
     * レベニューシェア案件の配分率検索を表示するか
     *
     * 選択されている案件種別がレベニューシェア、または全ての場合に表示する
     *
     * @return {boolean}
     */
    isDisplayRevenueBetween() {
      return this.form.type === '1' || this.form.type === ''
    },
  },
  // =========================================================
  // 監視プロパティ
  // =========================================================
  watch: {
    $route: {
      async handler() {
        await this.fetchPropositions()
      },
      immediate: true,
    },
  },
  // =========================================================
  // メソッド
  // =========================================================
  methods: {
    /**
     * 検索を行う
     */
    async submit() {
      // ボタンをローディング状態にする
      this.loading = true
      try {
        // ページを1に戻す
        this.$router.push('/propositions?page=1')
        // 検索条件保持
        this.search = this.form
        // 検索処理
        await this.fetchPropositions()
      } finally {
        // ボタンのローディングを解除する
        this.loading = false
      }
    },
    /**
     * 検索フォームの 表示/非表示 切り替え
     */
    toggle() {
      this.active = !this.active
    },
    /**
     * 検索フォームのリセット
     */
    reset() {
      this.form.free_word = ''
      this.form.status = '0'
      this.form.type = ''
      this.form.single_number_min = ''
      this.form.single_number_max = ''
      this.form.revenue_number_min = ''
      this.form.revenue_number_max = ''
      this.form.order = 'desc'
    },
    /**
     * 数値のリセット
     */
    numberReset() {
      this.form.single_number_min = ''
      this.form.single_number_max = ''
      this.form.revenue_number_min = ''
      this.form.revenue_number_max = ''
    },
    /**
     * 案件情報を取得する
     */
    async fetchPropositions() {
      this.propositions = null
      const response = await axios.get(`/api/propositions`, {
        params: {
          page: this.$route.query.page,
          free_word: this.search.free_word,
          status: this.search.status,
          type: this.search.type,
          single_number_min: this.search.single_number_min,
          single_number_max: this.search.single_number_max,
          revenue_number_min: this.search.revenue_number_min,
          revenue_number_max: this.search.revenue_number_max,
          order: this.search.order,
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
