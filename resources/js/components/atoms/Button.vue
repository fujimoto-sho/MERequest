<!--==========================================================================-->
<!-- ボタン -->
<!--==========================================================================-->
<template>
  <button
    :disabled="loading"
    :class="{
      'c-btn--primary': btnType === 'primary',
      'c-btn--secondary': btnType === 'secondary',
      'c-btn--ghost': btnType === 'ghost',
      'c-btn--loading': loading,
    }"
    @click="$emit('click', $event)"
    class="c-btn"
  >
    {{ btnText }}
  </button>
</template>

<script>
export default {
  // =========================================================
  // 親から渡ってくる値
  // =========================================================
  props: {
    btnType: { type: String, default: 'primary' },
    text: { type: String, default: '', require: true },
    loading: { type: Boolean, default: false },
    loadingText: { type: String, default: 'loading...' },
  },
  // =========================================================
  // 算出プロパティ
  // =========================================================
  computed: {
    /**
     * ロードしている最中は、テキストを変更する
     *
     * @returns {string}
     */
    btnText() {
      return this.loading ? this.loadingText : this.text
    },
  },
}
</script>
