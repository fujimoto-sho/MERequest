<!--==========================================================================-->
<!-- 入力のラベル -->
<!--==========================================================================-->
<template>
  <label :for="forName" :class="{ 'c-input__label--require': isRequire }" class="c-input__label">
    <slot></slot>
  </label>
</template>

<script>
export default {
  // =========================================================
  // 親から渡ってくる値
  // =========================================================
  props: {
    forName: { type: String, default: '' },
    isRequire: { type: Boolean },
  },
}
</script>
