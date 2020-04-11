import Vue from 'vue'
import VueRouter from 'vue-router'

import Top from './components/pages/Top.vue'
import Login from './components/pages/Login.vue'
import Register from './components/pages/Register.vue'
import PasswordEmail from './components/pages/PasswordEmail.vue'
import PasswordReset from './components/pages/PasswordReset.vue'
import Withdraw from './components/pages/Withdraw.vue'
import PasswordChange from './components/pages/PasswordChange.vue'
import Profile from './components/pages/UserEdit.vue'
import Proposition from './components/pages/Proposition.vue'
import PropositionCreate from './components/pages/PropositionCreate.vue'
import PropositionEdit from './components/pages/PropositionEdit.vue'
import PropositionShow from './components/pages/PropositionShow.vue'
import DirectMessage from './components/pages/DirectMessage.vue'
import DirectMessageList from './components/pages/DirectMessageList.vue'
import Mypage from './components/pages/Mypage.vue'
import MypageList from './components/pages/MypageList.vue'

import NotFound from './components/errors/NotFound.vue'
import SystemError from './components/errors/System.vue'

import store from './store'

// VueRouterの使用
Vue.use(VueRouter)

// ルーティング設定
const routes = [
  {
    path: '/',
    component: Top,
    meta: { loginUserToMypage: true },
  },
  {
    // ログイン
    path: '/login',
    component: Login,
    meta: { loginUserToMypage: true },
  },
  {
    // 新規登録
    path: '/register',
    component: Register,
    meta: { loginUserToMypage: true },
  },
  {
    // パスワード再設定（送信）
    path: '/password/email',
    component: PasswordEmail,
    meta: { loginUserToMypage: true },
  },
  {
    // パスワード再設定（リセット）
    path: '/password/reset/:token',
    component: PasswordReset,
    meta: { loginUserToMypage: true },
  },
  {
    // パスワード変更
    path: '/password/change',
    component: PasswordChange,
    meta: { notLoginUserToLogin: true },
  },
  {
    // 退会
    path: '/withdraw',
    component: Withdraw,
    meta: { notLoginUserToLogin: true },
  },
  {
    // プロフィール編集
    path: '/user/edit',
    component: Profile,
    meta: { notLoginUserToLogin: true },
  },
  {
    // 案件一覧
    path: '/propositions',
    component: Proposition,
    props: route => {
      const page = route.query.page
      return { page: /^[1-9][0-9]*$/.test(page) ? page : 1 }
    },
  },
  {
    // 案件登録
    path: '/propositions/create',
    meta: { notLoginUserToLogin: true },
    component: PropositionCreate,
  },
  {
    // 案件編集
    path: '/propositions/:id/edit',
    component: PropositionEdit,
    meta: { notLoginUserToLogin: true },
    props: true,
  },
  {
    // 案件詳細
    path: '/propositions/:id',
    name: 'PropositionShow',
    component: PropositionShow,
    props: true,
  },
  {
    // マイページ
    path: '/mypage',
    component: Mypage,
    props: route => {
      const id = route.query.id
      return { id: /^[1-9][0-9]*$/.test(id) ? id : null }
    },
  },
  {
    // マイページで表示している一覧
    path: '/mypage/:type/list',
    component: MypageList,
    props: route => {
      const page = route.query.page
      const type = route.params.type
      return {
        page: /^[1-9][0-9]*$/.test(page) ? page : 1,
        type: type,
      }
    },
  },
  {
    // ダイレクトメッセージ
    path: '/direct_messages/:applicationId',
    component: DirectMessage,
    meta: { notLoginUserToLogin: true },
    props: true,
  },
  {
    // ダイレクトメッセージ一覧
    path: '/direct_messages',
    component: DirectMessageList,
    meta: { notLoginUserToLogin: true },
    props: route => {
      const page = route.query.page
      return { page: /^[1-9][0-9]*$/.test(page) ? page : 1 }
    },
  },
  {
    // 500エラー
    path: '/500',
    component: SystemError,
  },
  {
    // 定義されているルート以外は404エラー
    path: '*',
    component: NotFound,
  },
]

const router = new VueRouter({
  mode: 'history',
  scrollBehavior() {
    return { x: 0, y: 0 }
  },
  routes,
})

router.beforeEach((to, from, next) => {
  // ページ遷移時、サイドバーが開いていたら閉じる
  if (store.getters['global/isSidebarOpen']) {
    store.commit('global/changeSidebarOpen')
  }

  // ページローディング
  store.commit('global/pageLoadingStart')

  if (to.matched.some(record => record.meta.loginUserToMypage) && store.getters['auth/check']) {
    // ログインユーザーだったらマイページに遷移
    next('/mypage')
  } else if (to.matched.some(record => record.meta.notLoginUserToLogin) && !store.getters['auth/check']) {
    // 非ログインユーザーだったらログインページに遷移
    next('/login')
  } else {
    // 以外はそのまま遷移
    next()
  }
})

router.afterEach(() => {
  store.commit('global/pageLoadingEnd')
})

export default router
