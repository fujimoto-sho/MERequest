<!--==========================================================================-->
<!-- 入力欄 -->
<!--==========================================================================-->
<template>
  <input
    :type="type"
    :name="name"
    :placeholder="placeholder"
    :value="value"
    @input="updateValue"
    @focus="$emit('focus', $event)"
    @blur="$emit('blur', $event)"
    class="c-input"
  />
</template>

<script>
export default {
  // =========================================================
  // 親から渡ってくる値
  // =========================================================
  props: {
    value: { default: null },
    name: { type: String, default: '', require: true },
    type: { type: String, default: 'text' },
    placeholder: { type: String, default: '' },
  },
  // =========================================================
  // メソッド
  // =========================================================
  /**
   * 値が変更されたことを親に伝える
   *
   * @param e
   */
  methods: {
    updateValue(e) {
      this.$emit('input', e.target.value)
      this.$emit('change', e.target.value)
    },
  },
}
</script>
