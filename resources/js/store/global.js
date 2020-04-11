// =========================================================
// ステート
// =========================================================
const state = {
  isSidebarOpen: false,
  flashMessage: '',
  pageLoading: false,
}

// =========================================================
// ゲッター
// =========================================================
const getters = {
  isSidebarOpen: state => state.isSidebarOpen,
}

// =========================================================
// ミューテーション
// =========================================================
const mutations = {
  /**
   * サイドバーのトグル状態を変更
   *
   * @param state 状態
   */
  changeSidebarOpen(state) {
    state.isSidebarOpen = !state.isSidebarOpen
  },

  /**
   * フラッシュメッセージをセットする
   *
   * @param state 状態
   * @param message セットするメッセージ
   */
  setFlashMessage(state, message) {
    state.flashMessage = message.message
    const timeout = 3000

    // フラッシュメッセージを変数timeoutで設定された分だけ表示する
    setTimeout(() => (state.flashMessage = ''), timeout)
  },

  /**
   * ページのロード開始
   *
   * @param state 状態
   */
  pageLoadingStart(state) {
    state.pageLoading = true
  },

  /**
   * ページのロード終了
   *
   * @param state 状態
   */
  pageLoadingEnd(state) {
    state.pageLoading = false
  },
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
}
