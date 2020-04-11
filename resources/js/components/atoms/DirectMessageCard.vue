<!--==========================================================================-->
<!-- ダイレクトメッセージカード -->
<!--==========================================================================-->
<template>
  <li class="u-flL">
    <RouterLink :to="'/direct_messages/' + application.id" class="c-card c-card--direct-message">
      <div class="c-card__title">
        {{ displayTitle }}
      </div>
      <hr class="c-card__border c-card__border--direct-message" />
      <div class="c-card__msg-title">最新のダイレクトメッセージ</div>
      <div class="c-card__msg u-text-oneline">{{ latestMessage }}</div>
      <figure class="c-card__user">
        <div class="c-avatar c-avatar--s">
          <img :src="opponentUser.avatar_url" alt="アバター" class="c-avatar__image" />
        </div>
        <figcaption class="c-card__username u-text-oneline">{{ opponentUser.name }}</figcaption>
      </figure>
    </RouterLink>
  </li>
</template>

<script>
export default {
  // =========================================================
  // 親から渡ってくる値
  // =========================================================
  props: {
    application: { type: Object, required: true },
    loginUser: { type: Object, required: true },
  },
  // =========================================================
  // 算出プロパティ
  // =========================================================
  computed: {
    /**
     * 最新のパブリックメッセージを返却する
     * メッセージがない場合は、その旨のメッセージを表示する
     *
     * @returns {string}
     */
    latestMessage() {
      const messageCount = this.application.direct_messages.length

      // メッセージがない場合は、その旨表示する
      if (messageCount === 0) {
        return 'まだメッセージがありません'
      }

      return this.application.direct_messages[messageCount - 1].message
    },

    /**
     * タイトルを制限する
     *
     * 最大26文字（全角で2行分）
     *
     * @returns {string}
     */
    displayTitle() {
      const title = this.application.proposition.title

      // タイトルが26文字を超える場合、制限する
      if (title.length > 26) {
        return title.substr(0, 25) + '…'
      }

      return title
    },

    /**
     * 相手ユーザー情報を返す
     *
     * ログインユーザーのid = 応募者のid：案件投稿ユーザー
     * ログインユーザーのid ≠ 応募者のid：案件応募ユーザー
     *
     * @return {string}
     */
    opponentUser() {
      return this.application.user.id === this.loginUser.id ? this.application.proposition.user : this.application.user
    },
  },
}
</script>
