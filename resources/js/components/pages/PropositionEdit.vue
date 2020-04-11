<!--==========================================================================-->
<!-- 案件編集ページ -->
<!--==========================================================================-->
<template>
  <div>
    <div v-if="loaded" class="c-container c-container--corp">
      <h1 class="c-title u-mb30">案件編集</h1>

      <form @submit.prevent="submit">
        <!--==========================================-->
        <!-- タイトル -->
        <!--==========================================-->
        <form-group>
          <template #label>
            <vue-label :is-require="true" for-name="title">タイトル</vue-label>
          </template>
          <vue-input id="title" v-model="form.title" name="title" placeholder="（例）youtubeの動画編集" />
          <input-error :errors="errors" name="title" />
        </form-group>

        <!--==========================================-->
        <!-- 金額 / レベニューシェア配分率 -->
        <!--==========================================-->
        <form-group>
          <template #label>
            <vue-label :is-require="true" for-name="number_min">{{ displayNumberTitle }}</vue-label>
          </template>
          <vue-input id="number_min" v-model="form.number_min" type="number" name="number_min" />
          <input-error :errors="errors" name="number_min" />
          <span class="c-input__tilda">〜</span>
          <vue-input id="number_max" v-model="form.number_max" type="number" name="number_max" />
          <input-error :errors="errors" name="number_max" />
          <span class="c-input__unit">{{ displayNumberUnit }}</span>
        </form-group>

        <!--==========================================-->
        <!-- 募集人数 -->
        <!--==========================================-->
        <form-group>
          <template #label>
            <vue-label :is-require="true" for-name="recruiting_count">募集人数</vue-label>
          </template>
          <vue-input id="recruiting_count" v-model="form.recruiting_count" type="number" name="recruiting_count" />
          <input-error :errors="errors" name="recruiting_count" />
        </form-group>

        <!--==========================================-->
        <!-- 募集状況 -->
        <!--==========================================-->
        <form-group>
          <template #label>
            <vue-label :is-require="true" for-name="">募集状況</vue-label>
          </template>
          <div class="c-radio__group">
            <input id="status-0" v-model="form.status" type="radio" name="status" class="c-radio" value="0" />
            <label for="status-0" class="c-radio__label">募集中</label>
          </div>
          <div class="c-radio__group">
            <input id="status-1" v-model="form.status" type="radio" name="status" class="c-radio" value="1" />
            <label for="status-1" class="c-radio__label">募集終了</label>
          </div>
          <input-error :errors="errors" name="status" />
        </form-group>

        <!--==========================================-->
        <!-- 内容 -->
        <!--==========================================-->
        <form-group>
          <template #label>
            <vue-label :is-require="true" for-name="content">内容</vue-label>
          </template>
          <vue-textarea
            id="content"
            v-model="form.content"
            name="content"
            placeholder="（例）youtubeで商品紹介をする動画を編集していただきたいです。。"
          />
          <input-error :errors="errors" name="content" />
        </form-group>

        <vue-button :loading="loading" text="変更" />
      </form>
    </div>
    <loading-overlay v-else />
  </div>
</template>

<script>
import { STATUS_OK, STATUS_UNPROCESSABLE_ENTITY } from '../../util'

export default {
  // =========================================================
  // タブに表示されるタイトル
  // =========================================================
  title: '案件編集',
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
        title: null,
        type: '0',
        number_min: null,
        number_max: null,
        recruiting_count: null,
        status: '0',
        content: '',
        token: this.$route.params.token,
      },
      errors: null,
      loading: false,
      loaded: false,
    }
  },
  // =========================================================
  // 算出プロパティ
  // =========================================================
  computed: {
    /**
     * 数値のタイトル
     *
     * @return {string}
     */
    displayNumberTitle() {
      return '金額'
    },
    /**
     * 数値の単位
     *
     * @return {string}
     */
    displayNumberUnit() {
      return '千円'
    },
  },
  watch: {
    $route: {
      async handler() {
        await this.fetchProposition()
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
     * 案件情報の取得
     *
     * axiosで通信を行い、返ってきたデータをセットする
     */
    async fetchProposition() {
      const response = await axios.get(`/api/propositions/${this.id}/edit`)

      // OKステータス以外は、エラーコードをセットする
      if (response.status !== STATUS_OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      // 返ってきたデータをセットする
      this.form.title = response.data.title
      this.form.type = response.data.type.toString()
      this.form.number_min = response.data.number_min_edit.toString()
      this.form.number_max = response.data.number_max_edit.toString()
      this.form.recruiting_count = response.data.recruiting_count
      this.form.status = response.data.status
      this.form.content = response.data.content
      this.loaded = true
    },
    /**
     * 案件更新処理
     *
     * axiosで通信を行い、案件の更新を行う
     *
     * 通信が成功したら更新した案件の詳細ページに遷移する
     */
    async submit() {
      // ボタンをローディング状態にする
      this.loading = true
      try {
        const response = await axios.put(`/api/propositions/${this.id}`, this.form)

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
          message: '案件を編集しました',
        })

        // 登録した案件の詳細ページに遷移
        this.$router.push(`/propositions/${this.id}`)
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
    /**
     * 数値のクリア
     */
    clearNumber() {
      this.form.number_min = null
      this.form.number_max = null
    },
  },
}
</script>
