import './bootstrap'
import Vue from 'vue'

// ルーティング定義
import router from './router'
// ストア定義
import store from './store'
// ルートコンポーネント
import App from './App.vue'

// mixin
import titleMixin from './mixins/title'

// コンポーネント
import Input from './components/atoms/Input.vue'
import Textarea from './components/atoms/Textarea.vue'
import InputError from './components/atoms/InputError.vue'
import Button from './components/atoms/Button.vue'
import Label from './components/atoms/Label.vue'
import FormGroup from './components/molecules/FormGroup.vue'
import Messages from './components/molecules/Messages.vue'
import DirectMessageCards from './components/molecules/DirectMessageCards.vue'
import PropositionCards from './components/molecules/PropositionCards.vue'
import LoadingOverlay from './components/globals/LoadingOverlay.vue'

// mixin
Vue.mixin(titleMixin)

// コンポーネントのグローバル登録
Vue.component('vue-input', Input)
Vue.component('vue-textarea', Textarea)
Vue.component('input-error', InputError)
Vue.component('vue-button', Button)
Vue.component('vue-label', Label)
Vue.component('form-group', FormGroup)
Vue.component('messages', Messages)
Vue.component('proposition-cards', PropositionCards)
Vue.component('direct-message-cards', DirectMessageCards)
Vue.component('loading-overlay', LoadingOverlay)

// Vue インスタンスの作成
const createApp = async () => {
  // ログインユーザー情報の取得
  // リロードした際、ログイン情報がクリアされてしまうことの対策
  await store.dispatch('auth/currentUser')

  new Vue({
    el: '#app',
    router,
    store,
    components: { App },
    template: '<App />',
  })
}

createApp()
