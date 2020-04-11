<!--==========================================================================-->
<!-- 案件登録ページ -->
<!--==========================================================================-->
<template>
  <div class="c-container c-container--corp">
    <h1 class="c-title u-mb30">案件登録</h1>

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
          placeholder="（例）youtubeで商品紹介をする動画を編集していただきたいです。"
        />
        <input-error :errors="errors" name="content" />
      </form-group>

      <vue-button :loading="loading" text="登録" />
    </form>
  </div>
</template>

<script>
import { STATUS_CREATED, STATUS_UNPROCESSABLE_ENTITY } from '../../util'

export default {
  // =========================================================
  // タブに表示されるタイトル
  // =========================================================
  title: '案件登録',
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
     * 案件登録処理
     *
     * axiosで通信を行い、案件の登録を行う
     *
     * 通信が成功したら登録した案件の詳細ページに遷移する
     */
    async submit() {
      // ボタンをローディング状態にする
      this.loading = true
      try {
        const response = await axios.post('/api/propositions', this.form)

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
          message: '案件を登録しました',
        })

        // 登録した案件の詳細ページに遷移
        this.$router.push(`/propositions/${response.data.id}`)
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
