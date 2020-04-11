<!--==========================================================================-->
<!-- フォームグループ（入力とラベルをワンセットにする） -->
<!--==========================================================================-->
<template>
  <dl class="c-form-group">
    <dt class="c-form-group__title">
      <slot name="label"></slot>
    </dt>
    <dd class="c-form-group__input">
      <slot></slot>
    </dd>
  </dl>
</template>
