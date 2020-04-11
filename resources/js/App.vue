<template>
  <div class="l-wrapper">
    <div id="top"></div>
    <Header />
    <FlashMessage />
    <Sidebar />
    <main class="l-main">
      <transition name="view">
        <RouterView />
      </transition>
    </main>
    <Footer />
    <ScrollTop />
  </div>
</template>

<script>
import Header from './components/globals/Header.vue'
import Footer from './components/globals/Footer.vue'
import ScrollTop from './components/globals/ScrollTop.vue'
import Sidebar from './components/globals/Sidebar.vue'
import FlashMessage from './components/globals/FlashMessage.vue'
import { STATUS_NOT_FOUND, STATUS_UNAUTHORIZED, STATUS_INTERNAL_SERVER_ERROR } from './util'

export default {
  // =========================================================
  // コンポーネント
  // =========================================================
  components: {
    ScrollTop,
    Header,
    Footer,
    Sidebar,
    FlashMessage,
  },
  // =========================================================
  // 算出プロパティ
  // =========================================================
  computed: {
    /**
     * エラーコードを返却する
     */
    errorCode() {
      return this.$store.state.error.code
    },
  },
  // =========================================================
  // 監視プロパティ
  // =========================================================
  watch: {
    /**
     * エラーコードを監視する
     */
    errorCode: {
      async handler(val) {
        if (val === STATUS_INTERNAL_SERVER_ERROR) {
          // サーバーエラーの場合はサーバーエラーページに遷移する
          this.$router.push('/500')
        } else if (val === STATUS_UNAUTHORIZED) {
          // 認証エラーの場合は、ストアのuserをクリアし、ログイン画面に遷移する
          this.$store.commit('auth/setUser', null)
          this.$router.push('/login')
        } else if (val === STATUS_NOT_FOUND) {
          // 該当するページなしの場合はnot-foundエラーページに遷移する
          this.$router.push('/not-found')
        }
      },
      immediate: true,
    },
    $route() {
      // 起動時にエラーコードをクリアする
      this.$store.commit('error/setCode', null)
    },
  },
}
</script>
