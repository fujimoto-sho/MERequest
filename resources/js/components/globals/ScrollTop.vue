<!--==========================================================================-->
<!-- スクロールトップ -->
<!--==========================================================================-->
<template>
  <div v-scroll="handleScroll" :class="{ 'u-show': visible }" class="p-scroll-top">
    <a v-smooth-scroll href="#top" class="p-scroll-top__link">
      <span class="typcn typcn-arrow-up p-scroll-top__icon"></span>
    </a>
  </div>
</template>

<script>
import Vue from 'vue'
import vueSmoothScroll from 'vue2-smooth-scroll'

Vue.use(vueSmoothScroll)

// カスタムスクロールディレクティブ
Vue.directive('scroll', {
  inserted: function(el, binding) {
    const f = function(evt) {
      if (binding.value(evt, el)) {
        window.removeEventListener('scroll', f)
      }
    }
    window.addEventListener('scroll', f)
  },
})

export default {
  // =========================================================
  // データ
  // =========================================================
  data() {
    return {
      visible: false,
    }
  },
  // =========================================================
  // メソッド
  // =========================================================
  methods: {
    /**
     * 150px以上スクロールしたら、スクロールトップが表示されるようにする
     */
    handleScroll() {
      this.visible = window.pageYOffset > 150
    },
  },
}
</script>
