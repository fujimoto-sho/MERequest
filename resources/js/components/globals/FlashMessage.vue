<!--==========================================================================-->
<!-- フラッシュメッセージ -->
<!--==========================================================================-->
<template>
  <transition name="p-flash-message">
    <div v-show="message" class="p-flash-message">
      {{ message }}
    </div>
  </transition>
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
      // フラッシュメッセージ
      message: state => state.global.flashMessage,
    }),
  },
}
</script>
