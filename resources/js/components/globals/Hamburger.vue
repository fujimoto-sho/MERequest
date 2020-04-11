<!--==========================================================================-->
<!-- ハンバーガーメニュー -->
<!--==========================================================================-->
<template>
  <div class="p-hamburger">
    <div :class="{ 'p-hamburger__content--active': active }" @click="changeActive" class="p-hamburger__content">
      <span></span>
      <span></span>
      <span></span>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'

export default {
  // =========================================================
  // 算出プロパティ
  // =========================================================
  computed: {
    /**
     * ストアからstateを取得する
     */
    ...mapState({
      // サイドバーのアクティブ判定
      active: state => state.global.isSidebarOpen,
    }),
  },
  // =========================================================
  // メソッド
  // =========================================================
  methods: {
    /**
     * サイドバーの アクティブ/非アクティブ を切り替える
     */
    changeActive() {
      this.$store.commit('global/changeSidebarOpen')
    },
  },
}
</script>
