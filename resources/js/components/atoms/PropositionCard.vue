<!--==========================================================================-->
<!-- 案件のカード -->
<!--==========================================================================-->
<template>
  <li class="u-flL">
    <RouterLink :to="'/propositions/' + proposition.id" class="c-card">
      <div v-if="proposition.is_new" class="c-card__badge">New</div>
      <div class="c-card__title">
        {{ displayTitle }}
      </div>
      <hr class="c-card__border" />
      <div class="c-card__num">
        <span>{{ proposition.formatted_number_min }}</span>
        <span>〜</span>
        <span>{{ proposition.formatted_number_max }}</span>
      </div>
      <div class="c-card__msg-title">最新のパブリックメッセージ</div>
      <div class="c-card__msg u-text-oneline">{{ latestMessage }}</div>
      <figure class="c-card__user">
        <div class="c-avatar c-avatar--s">
          <img :src="proposition.user.avatar_url" alt="アバター" class="c-avatar__image" />
        </div>
        <figcaption class="c-card__username u-text-oneline">{{ proposition.user.name }}</figcaption>
      </figure>
      <div class="c-card__bottom">
        <div :class="{ 'c-card__type--single': isSingle, 'c-card__type--revenue': !isSingle }" class="c-card__type">
          {{ proposition.type_name }}
        </div>
        <div :class="{ 'c-card__like--active': proposition.is_like }" @click.prevent="like" class="c-card__like">
          <span class="typcn typcn-star-full-outline u-fs-xl"></span>
          <span class="c-card__like-count">{{ proposition.likes_count }}</span>
        </div>
      </div>
    </RouterLink>
  </li>
</template>

<script>
export default {
  // =========================================================
  // 親から渡ってくる値
  // =========================================================
  props: {
    proposition: { type: Object, required: true },
  },
  // =========================================================
  // 算出プロパティ
  // =========================================================
  computed: {
    /**
     * 案件が単発かを判定する
     *
     * 単発：true
     * レベニューシェア：false
     *
     * @returns {boolean}
     */
    isSingle() {
      return this.proposition.type === 0
    },
    /**
     * 最新のパブリックメッセージを取得する
     * メッセージがない場合は、その旨のメッセージを表示する
     *
     * @returns {string}
     */
    latestMessage() {
      const messageCount = this.proposition.messages.length

      // メッセージがない場合は、その旨表示する
      if (messageCount === 0) {
        return 'まだメッセージがありません'
      }

      return this.proposition.messages[messageCount - 1].message
    },
    /**
     * タイトルを制限する
     *
     * 最大26文字（全角で2行分）
     *
     * @returns {string}
     */
    displayTitle() {
      const title = this.proposition.title

      // タイトルが26文字を超える場合、制限する
      if (title.length > 26) {
        return title.substr(0, 25) + '…'
      }

      return title
    },
  },
  // =========================================================
  // メソッド
  // =========================================================
  methods: {
    /**
     * いいね情報を親に伝える
     */
    like() {
      this.$emit('like', {
        id: this.proposition.id,
        liked: this.proposition.is_like,
      })
    },
  },
}
</script>
