<!--==========================================================================-->
<!-- プロフィール編集ページ -->
<!--==========================================================================-->
<template>
  <div class="c-container c-container--corp">
    <h1 class="c-title u-mb30">プロフィール編集</h1>

    <form @submit.prevent="submit">
      <!--==========================================-->
      <!-- ユーザー名 -->
      <!--==========================================-->
      <form-group>
        <template #label>
          <vue-label :is-require="true" for-name="name">ユーザー名</vue-label>
        </template>
        <vue-input id="name" v-model="form.name" name="name" />
        <input-error :errors="errors" name="name" />
      </form-group>

      <!--==========================================-->
      <!-- メールアドレス -->
      <!--==========================================-->
      <form-group>
        <template #label>
          <vue-label :is-require="true" for-name="email">メールアドレス</vue-label>
        </template>
        <vue-input id="email" v-model="form.email" name="email" />
        <input-error :errors="errors" name="email" />
      </form-group>

      <!--==========================================-->
      <!-- 自己紹介 -->
      <!--==========================================-->
      <form-group>
        <template #label>
          <vue-label :is-require="false" for-name="bio">自己紹介</vue-label>
        </template>
        <vue-textarea id="bio" v-model="form.bio" name="bio" />
        <input-error :errors="errors" name="bio" />
      </form-group>

      <!--==========================================-->
      <!-- アイコン画像 -->
      <!--==========================================-->
      <form-group>
        <template #label>
          <vue-label :is-require="false" for-name="image">アイコン画像</vue-label>
        </template>
        <input id="image" @change="onFileChange" type="file" name="image" class="js-input-clear" />
        <output class="c-avatar c-avatar--xl">
          <img :src="preview" alt="" class="c-avatar__image" />
        </output>
        <input-error :errors="errors" name="image" />
      </form-group>

      <vue-button :loading="loading" text="変更" />
    </form>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import { STATUS_OK, STATUS_UNPROCESSABLE_ENTITY } from '../../util'

export default {
  // =========================================================
  // タブに表示されるタイトル
  // =========================================================
  title: 'プロフィール編集',
  // =========================================================
  // データ
  // =========================================================
  data() {
    return {
      form: {
        name: '',
        email: '',
        bio: '',
        image: null,
        token: this.$route.params.token,
      },
      preview: null,
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
  },
  // =========================================================
  // ライフサイクルフック created
  // データの監視とイベントの初期セットアップが完了した状態で呼ばれる
  // =========================================================
  created() {
    // フォームのエラーをクリア
    this.clearError()

    // ユーザーの初期値を設定
    this.form.name = this.user.name
    this.form.email = this.user.email
    this.form.bio = this.user.profile !== null ? this.user.profile.bio : ''
    this.preview = this.user.avatar_url
  },
  // =========================================================
  // メソッド
  // =========================================================
  methods: {
    /**
     * プロフィール更新処理
     */
    async submit() {
      // ボタンをローディング状態にする
      this.loading = true
      try {
        // 画像をPOSTするため、FormDataを使用する
        const formData = new FormData()
        formData.append('name', this.form.name)
        formData.append('email', this.form.email)
        if (this.form.bio !== null) {
          formData.append('bio', this.form.bio)
        }
        if (this.form.image !== null) {
          formData.append('image', this.form.image)
        }

        const response = await axios.post('/api/user/update', formData)

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
          message: 'プロフィールを変更しました',
        })

        // 更新したユーザーデータを取得する
        await this.$store.dispatch('auth/currentUser')

        // マイページへ遷移する
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
    /**
     * ファイルの読み込み
     */
    onFileChange(event) {
      // 選択なしは処理中断
      if (event.target.files.length === 0) {
        return false
      }

      // ファイルが画像ではなかったら処理中断
      if (!event.target.files[0].type.match('image.*')) {
        return false
      }

      // FileReaderクラスのインスタンスを取得
      const reader = new FileReader()

      // ファイルを読み込み終わったタイミングで実行する処理
      reader.onload = e => {
        // previewに読み込み結果（データURL）を代入する
        this.preview = e.target.result
      }

      // ファイルを読み込む
      reader.readAsDataURL(event.target.files[0])

      this.form.image = event.target.files[0]
    },
  },
}
</script>
