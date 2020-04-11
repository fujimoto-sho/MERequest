module.exports = {
  root: true,
  parser: 'vue-eslint-parser', // parserをvue用に指定
  parserOptions: {
    parser: 'babel-eslint', // es6用のparserはoptionsの仲へ
    sourceType: 'module',
  },
  env: {
    browser: true,
    node: true,
  },
  extends: [
    'eslint:recommended', // eslintの推奨ルール
    'standard', // standardルール https://github.com/standard/standard/blob/master/docs/RULES-en.md
    'plugin:prettier/recommended', // prettierの推奨ルール
    'plugin:vue/recommended', // vueの推奨ルール
    'prettier/vue', // eslint-config-prettierのvue用ルールを適用
  ],
  globals: {
    axios: false, // axiosはグローバルに存在し、書き換え不可
  },
  rules: {
    'no-new': 'off', // new Vueを許可するため
    'no-plusplus': 'off', // インクリメント許可
    'func-names': 'off', // 無名関数を許可
    'vue/component-name-in-template-casing': 'off', // ケバブケース
    'vue/require-prop-types': 'off', // propのtypeの未指定を許可
  },
}
