<!--==========================================================================-->
<!-- ページネーション -->
<!--==========================================================================-->
<template>
  <ul class="c-pagination">
    <li v-if="isDisplayFirstPage" class="c-pagination__item">
      <RouterLink :to="`${routerPath}?page=1`" class="c-pagination__link c-pagination__link--arrow">
        <span class="typcn typcn-media-rewind"></span>
      </RouterLink>
    </li>
    <li
      v-for="pageNumber in pageNumbers"
      :key="pageNumber"
      :class="{ 'c-pagination__item--active': isActivePage(pageNumber) }"
      class="c-pagination__item"
    >
      <RouterLink
        :to="`${routerPath}?page=${pageNumber}`"
        :class="{ 'c-pagination__link--active': isActivePage(pageNumber) }"
        class="c-pagination__link"
      >
        {{ pageNumber }}
      </RouterLink>
    </li>
    <li v-if="isDisplayLastPage" class="c-pagination__item">
      <RouterLink :to="`${routerPath}?page=${lastPage}`" class="c-pagination__link c-pagination__link--arrow">
        <span class="typcn typcn-media-fast-forward"></span>
      </RouterLink>
    </li>
  </ul>
</template>

<script>
export default {
  // =========================================================
  // 親から渡ってくる値
  // =========================================================
  props: {
    routerPath: { type: String, required: true },
    currentPage: { type: Number, required: true },
    lastPage: { type: Number, required: true },
  },
  // =========================================================
  // 算出プロパティ
  // =========================================================
  computed: {
    /**
     * 画面に表示されるページ番号の配列を作成する
     *
     * 現在ページを中央とし、最大5ページ分表示する
     *
     * 最大ページが5以下の場合はすべて表示
     * 現在のページが3以下の場合、5ページまで表示
     *
     * @return {array}
     */
    pageNumbers() {
      // 最大ページが5以下の場合はすべて表示する
      if (this.lastPage <= 5) {
        return Array.from(new Array(this.lastPage)).map((v, i) => ++i)
      }
      // 現在のページが3以下の場合、5ページまで表示する
      if (this.currentPage <= 3) {
        return Array.from(new Array(5)).map((v, i) => ++i)
      }
      // 現在ページは中央に表示する
      let centerNumber = this.currentPage - 2
      if (centerNumber > this.lastPage - 4) {
        centerNumber = this.lastPage - 4
      }
      return Array.from(new Array(5)).map((v, i) => i + centerNumber)
    },
    /**
     * 表示されているページナンバーに「1」が含まれていなかったら、最初のページに戻るボタンを作成する
     *
     * @return {boolean}
     */
    isDisplayFirstPage() {
      return !this.pageNumbers.includes(1)
    },
    /**
     * 表示されているページナンバーに最終ページが含まれていなかったら、最終ページに進むボタンを作成する
     *
     * @return {boolean}
     */
    isDisplayLastPage() {
      return !this.pageNumbers.includes(this.lastPage)
    },
  },
  // =========================================================
  // メソッド
  // =========================================================
  methods: {
    /**
     * 現在のページかを判定する
     *
     * 渡されたページ番号 = 現在のページ：true
     * 渡されたページ番号 ≠ 現在のページ：false
     *
     * @param pageNumber ページ番号
     * @returns {boolean}
     */
    isActivePage(pageNumber) {
      return pageNumber === this.currentPage
    },
  },
}
</script>
