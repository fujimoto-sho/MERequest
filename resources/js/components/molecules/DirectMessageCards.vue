<!--==========================================================================-->
<!-- ダイレクトメッセージカード一覧 -->
<!--==========================================================================-->
<template>
  <div>
    <ul v-if="applications.length > 0" class="u-clearfix">
      <direct-message-card
        v-for="application in applications"
        :key="application.id"
        :application="application"
        :login-user="loginUser"
      />
    </ul>
    <div v-else class="u-mtb10 u-pl10 u-pr10">対象のダイレクトメッセージがありません。</div>
  </div>
</template>

<script>
import DirectMessageCard from '../atoms/DirectMessageCard.vue'
import { mapState } from 'vuex'

export default {
  // =========================================================
  // コンポーネント
  // =========================================================
  components: {
    DirectMessageCard,
  },
  // =========================================================
  // 親から渡ってくる値
  // =========================================================
  props: {
    applications: { type: Array, required: true },
  },
  // =========================================================
  // 算出プロパティ
  // =========================================================
  computed: {
    /**
     * ストアからstateを取得する
     */
    ...mapState({
      // ログインユーザー情報
      loginUser: state => state.auth.user,
    }),
  },
}
</script>
