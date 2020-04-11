// lodashの読み込み
window._ = require('lodash')

// axiosの読み込み
window.axios = require('axios')

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

// csrfトークンの存在チェック
const token = document.head.querySelector('meta[name="csrf-token"]')
if (token) {
  window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content
} else {
  console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token')
}

window.axios.interceptors.response.use(response => response, error => error.response || error)
