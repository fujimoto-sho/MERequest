// =========================================================
// 表示するページカウントのミックスイン
// =========================================================
export default {
  // =========================================================
  // 算出プロパティ
  // =========================================================
  computed: {
    /**
     * 現在表示している最小ページを返却する
     *
     * @returns {number}
     */
    displayMinCount() {
      const minCount = (this.currentPage - 1) * 10 + 1
      if (this.total === 0) {
        return 0
      }
      return minCount
    },
    /**
     * 現在表示している最小ページを返却する
     *
     * @returns {number}
     */
    displayMaxCount() {
      const maxCount = this.currentPage * 10
      if (maxCount > this.total) {
        return this.total
      }
      return maxCount
    },
  },
}
