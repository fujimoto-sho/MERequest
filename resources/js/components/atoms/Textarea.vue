<!--==========================================================================-->
<!-- テキストエリア -->
<!--==========================================================================-->
<template>
  <textarea
    :name="name"
    :placeholder="placeholder"
    :value="value"
    @input="updateValue"
    @focus="$emit('focus', $event)"
    @blur="$emit('blur', $event)"
    class="c-input c-input--textarea"
  >
  </textarea>
</template>

<script>
export default {
  // =========================================================
  // 親から渡ってくる値
  // =========================================================
  props: {
    value: { type: String, default: '' },
    name: { type: String, default: '', require: true },
    placeholder: { type: String, default: '' },
  },
  // =========================================================
  // メソッド
  // =========================================================
  methods: {
    /**
     * 値が変更されたことを親に伝える
     *
     * @param e
     */
    updateValue(e) {
      this.$emit('input', e.target.value)
      this.$emit('change', e.target.value)
    },
  },
}
</script>
