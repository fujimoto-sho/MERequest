const mix = require('laravel-mix')
const StyleLintPlugin = require('stylelint-webpack-plugin')
require('laravel-mix-polyfill')

mix.webpackConfig({
  module: {
    rules: [
      {
        test: /\.(js|vue)?$/,
        enforce: 'pre',
        loader: 'eslint-loader',
        exclude: /node_modules/,
      },
      {
        test: /\.scss/,
        enforce: 'pre',
        loader: 'import-glob-loader',
      },
    ],
  },
  plugins: [
    new StyleLintPlugin({
      files: ['resources/sass/*/**/*.scss'],
      configFile: path.join(__dirname, '.stylelintrc.yml'),
      syntax: 'scss',
    }),
  ],
})

mix
  .browserSync('merequest.test')
  .js('resources/js/app.js', 'public/js')
  .sass('resources/sass/app.scss', 'public/css')
  .polyfill({
    enabled: true,
    useBuiltIns: 'usage',
    targets: { firefox: '50', ie: 11 },
  })
  .version()
