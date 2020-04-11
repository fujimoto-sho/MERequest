<!--==========================================================================-->
<!-- ヘッダー -->
<!--==========================================================================-->
<template>
  <header class="l-header">
    <div class="l-header__left">
      <Hamburger />
      <RouterLink v-if="!isLogin" class="l-header__title u-ml15" to="/">MERequest</RouterLink>
      <RouterLink v-if="isLogin" class="l-header__title u-ml15" to="/propositions">MERequest</RouterLink>
    </div>
    <nav class="l-header__menu">
      <ul class="l-header__items">
        <li v-if="!isLogin" class="l-header__item">
          <RouterLink class="l-header__link" to="/login">ログイン</RouterLink>
        </li>
        <li v-if="!isLogin" class="l-header__item">
          <RouterLink class="l-header__link" to="/register">会員登録（無料）</RouterLink>
        </li>
        <li v-if="isLogin" class="l-header__item">
          <a @click="logout" class="l-header__link">ログアウト</a>
        </li>
      </ul>
    </nav>
  </header>
</template>

<script>
import { mapState, mapGetters } from 'vuex'
import Hamburger from './Hamburger.vue'

export default {
  // =========================================================
  // コンポーネント
  // =========================================================
  components: {
    Hamburger,
  },
  // =========================================================
  // 算出プロパティ
  // =========================================================
  computed: {
    /**
     * ストアからstateを取得する
     */
    ...mapState({
      // ステータスが正常値か
      apiStatus: state => state.auth.apiStatus,
    }),
    /**
     * ストアからgettersを取得する
     */
    ...mapGetters({
      // ログインしているか
      isLogin: 'auth/check',
    }),
  },
  // =========================================================
  // メソッド
  // =========================================================
  methods: {
    /**
     * ログアウト
     */
    async logout() {
      await this.$store.dispatch('auth/logout')

      if (this.apiStatus) {
        // ログアウトが正常に終了していたら、ログイン画面に遷移する
        this.$router.push('/login')
      }
    },
  },
}
</script>
