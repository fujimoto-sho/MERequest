import { STATUS_CREATED, STATUS_OK, STATUS_UNPROCESSABLE_ENTITY } from '../util'
import store from './index'

// =========================================================
// ステート
// =========================================================
const state = {
  user: null,
  apiStatus: null,
  errorMessages: null,
}

// =========================================================
// ゲッター
// =========================================================
const getters = {
  check: state => !!state.user, // 二重否定で必ずBoolean型を返すようにする
}

// =========================================================
// ミューテーション
// =========================================================
const mutations = {
  /**
   * ユーザー情報をセットする
   *
   * @param state 状態
   * @param user ユーザー情報
   */
  setUser(state, user) {
    state.user = user
  },
  /**
   * ステータスをセットする
   *
   * apiStatusの値
   * ステータスが正常：true
   * ステータスが異常：false
   *
   * @param state 状態
   * @param apiStatus ステータスが正常か
   */
  setApiStatus(state, apiStatus) {
    state.apiStatus = apiStatus
  },
  /**
   * エラーメッセージをセットする
   *
   * @param state 状態
   * @param messages エラーメッセージ
   */
  setErrorMessages(state, messages) {
    state.errorMessages = messages
  },
}

// =========================================================
// アクション
// =========================================================
const actions = {
  /**
   * ログイン中のユーザーを取得
   *
   * 取得できた場合は、stateのuserにそのユーザー情報をセットする
   *
   * @param context
   * @param data
   */
  async currentUser(context, data) {
    const response = await axios.get('/api/user')
    const user = response.data || null

    // ステータスが正常の場合は、その旨セットする
    if (response.status === STATUS_OK) {
      context.commit('setApiStatus', true)
      context.commit('setUser', user)
      return
    }

    // ステータスが正常でない場合は、その旨セットする
    context.commit('setApiStatus', false)
    context.commit('error/setCode', response.status, { root: true })
  },

  /**
   * ユーザー登録
   *
   * axiosで通信を行い、ユーザー登録を行う
   *
   * 通信後、成功した場合はユーザー情報が返ってくるので、stateのuserにセットする
   *
   * @param context
   * @param data
   */
  async register(context, data) {
    context.commit('setApiStatus', null)
    const response = await axios.post('/api/register', data)

    // ステータスが正常の場合は、その旨セットする
    if (response.status === STATUS_CREATED) {
      context.commit('setApiStatus', true)
      // ユーザー情報をセットする
      await store.dispatch('auth/currentUser')
      return
    }

    // ステータスが正常でない場合は、その旨セットする
    context.commit('setApiStatus', false)
    if (response.status === STATUS_UNPROCESSABLE_ENTITY) {
      // バリデーションエラーの場合は、そのメッセージをセットする
      context.commit('setErrorMessages', response.data.errors)
    } else {
      // バリデーション以外のエラーは、エラーコードをセットする
      context.commit('error/setCode', response.status, { root: true })
    }
  },

  /**
   * ログイン
   *
   * axiosで通信を行い、ログインを行う
   *
   * 通信後、成功した場合はユーザー情報が返ってくるので、stateのuserにセットする
   *
   * @param context
   * @param data
   */
  async login(context, data) {
    context.commit('setApiStatus', null)
    const response = await axios.post('/api/login', data).catch(err => err.response || err)

    // ステータスが正常の場合は、その旨セットする
    if (response.status === STATUS_OK) {
      context.commit('setApiStatus', true)
      return false
    }

    // ステータスが正常でない場合は、その旨セットする
    context.commit('setApiStatus', false)
    if (response.status === STATUS_UNPROCESSABLE_ENTITY) {
      // バリデーションエラーの場合は、そのメッセージをセットする
      context.commit('setErrorMessages', response.data.errors)
    } else {
      // バリデーション以外のエラーは、エラーコードをセットする
      context.commit('error/setCode', response.status, { root: true })
    }
  },

  /**
   * ログアウト
   *
   * axiosで通信を行い、ログアウトを行う
   *
   * 通信後、成功した場合stateのuserをnullにする
   *
   * @param context
   */
  async logout(context) {
    context.commit('setApiStatus', null)
    const response = await axios.post('/api/logout')

    // ステータスが正常の場合は、その旨セットする
    if (response.status === STATUS_OK) {
      context.commit('setApiStatus', true)
      // ユーザー情報をnullにする
      context.commit('setUser', null)
      return
    }

    // ステータスが正常でない場合は、その旨セットする
    context.commit('setApiStatus', false)
    context.commit('error/setCode', response.status, { root: true })
  },

  /**
   * パスワード再設定（送信）
   *
   * axiosで通信を行い、パスワードの再設定に必要なメールを送信する
   *
   * @param context
   * @param data
   */
  async passwordEmail(context, data) {
    context.commit('setApiStatus', null)
    const response = await axios.post('/api/password/email', data).catch(err => err.response || err)

    // ステータスが正常の場合は、その旨セットする
    if (response.status === STATUS_OK) {
      context.commit('setApiStatus', true)
      return
    }

    // ステータスが正常でない場合は、その旨セットする
    context.commit('setApiStatus', false)
    if (response.status === STATUS_UNPROCESSABLE_ENTITY) {
      // バリデーションエラーの場合は、そのメッセージをセットする
      context.commit('setErrorMessages', response.data.errors)
    } else {
      // バリデーション以外のエラーは、エラーコードをセットする
      context.commit('error/setCode', response.status, { root: true })
    }
  },

  /**
   * パスワード再設定（リセット）
   *
   * axiosで通信を行い、パスワードの再設定を行う
   *
   * @param context
   * @param data
   */
  async passwordReset(context, data) {
    context.commit('setApiStatus', null)
    const response = await axios.post('/api/password/reset', data).catch(err => err.response || err)

    // ステータスが正常の場合は、その旨セットする
    if (response.status === STATUS_OK) {
      context.commit('setApiStatus', true)
      return
    }

    // ステータスが正常でない場合は、その旨セットする
    context.commit('setApiStatus', false)
    if (response.status === STATUS_UNPROCESSABLE_ENTITY) {
      // バリデーションエラーの場合は、そのメッセージをセットする
      context.commit('setErrorMessages', response.data.errors)
    } else {
      // バリデーション以外のエラーは、エラーコードをセットする
      context.commit('error/setCode', response.status, { root: true })
    }
  },
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
}
