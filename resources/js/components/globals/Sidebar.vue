<!--==========================================================================-->
<!-- サイドバー -->
<!--==========================================================================-->
<template>
  <div :class="{ 'l-sidebar--active': active }" class="l-sidebar">
    <ul>
      <li v-for="item in displayItems" :key="item.name" class="l-sidebar__item">
        <RouterLink v-if="item.link !== ''" :to="item.link" class="l-sidebar__link">{{ item.name }}</RouterLink>
        <a v-else @click="item.click" class="l-sidebar__link">{{ item.name }}</a>
      </li>
    </ul>
  </div>
</template>

<script>
import { mapState } from 'vuex'

export default {
  // =========================================================
  // データ
  // =========================================================
  data() {
    return {
      loginItems: [
        { name: 'マイページ', link: '/mypage', click: '' },
        { name: '案件を探す', link: '/propositions', click: '' },
        { name: '案件を登録する', link: '/propositions/create', click: '' },
        { name: 'パブリックメッセージ', link: '/mypage/message/list', click: '' },
        { name: 'ダイレクトメッセージ', link: '/direct_messages', click: '' },
        { name: 'プロフィール編集', link: '/user/edit', click: '' },
        { name: 'パスワード変更', link: '/password/change', click: '' },
        { name: '退会', link: '/withdraw', click: '' },
        { name: 'ログアウト', link: '', click: this.logout },
      ],
      notLoginItems: [
        { name: '会員登録（無料）', link: '/register', click: '' },
        { name: 'ログイン', link: '/login', click: '' },
        { name: '案件を探す', link: '/propositions', click: '' },
      ],
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
      // ステータスが正常値か
      apiStatus: state => state.auth.apiStatus,
      // サイドバーのアクティブ判定
      active: state => state.global.isSidebarOpen,
    }),

    /**
     * ログイン状態によって、サイドバーに表示するメニューを切り替える
     *
     * @return {array}
     */
    displayItems() {
      return this.$store.getters['auth/check'] ? this.loginItems : this.notLoginItems
    },
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
