// =========================================================
// タブに表示されるタイトル
// =========================================================
export default {
  mounted() {
    let { title } = this.$options
    if (title) {
      // タイトルが設定されていたらそのタイトル+matchをセット
      // 関数の場合は戻り地を取得する
      title = typeof title === 'function' ? title.call(this) : title
      document.title = `${title} - MERequest`
    }
  },
}
