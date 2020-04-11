<!--==========================================================================-->
<!-- メッセージ一覧 -->
<!--==========================================================================-->
<template>
  <ul v-if="messages.length > 0">
    <li v-for="message in messages" :key="message.id" class="c-message u-mb15">
      <div class="c-message__left">
        <figure>
          <div class="c-avatar c-avatar--m">
            <img :src="message.user.avatar_url" alt="アバター" class="c-avatar__image" />
          </div>
          <figcaption v-if="isPostUser(message.user.id)" class="c-message__user-caption u-mt5">投稿者</figcaption>
        </figure>
      </div>
      <div class="c-message__right">
        <div class="c-message__username">
          {{ message.user.name }}
        </div>
        <div class="c-message__balloon">
          <p>
            {{ message.message }}
          </p>
          <time class="c-message__date">{{ message.created_at }}</time>
        </div>
      </div>
    </li>
  </ul>
</template>

<script>
export default {
  // =========================================================
  // 親から渡ってくる値
  // =========================================================
  props: {
    messages: { type: Array, required: true },
    propositionUserId: { type: Number, required: true },
  },
  // =========================================================
  // メソッド
  // =========================================================
  methods: {
    /**
     * 案件投稿ユーザー判定
     *
     * 案件投稿ユーザー = メッセージ投稿ユーザー：true
     * 案件投稿ユーザー ≠ メッセージ投稿ユーザー：false
     *
     * @returns {boolean}
     */
    isPostUser(userId) {
      return userId === this.propositionUserId
    },
  },
}
</script>
