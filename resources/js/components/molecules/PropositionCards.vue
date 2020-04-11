<!--==========================================================================-->
<!-- 案件カード一覧 -->
<!--==========================================================================-->
<template>
  <div>
    <ul v-if="propositions.length > 0" class="u-clearfix">
      <proposition-card
        v-for="proposition in propositions"
        :key="idPrefix + proposition.id"
        :proposition="proposition"
        @like="like"
      />
    </ul>
    <div v-else class="u-mtb10 u-pl10 u-pr10">対象の案件がありません。</div>
  </div>
</template>

<script>
import PropositionCard from '../atoms/PropositionCard.vue'

export default {
  // =========================================================
  // コンポーネント
  // =========================================================
  components: {
    PropositionCard,
  },
  // =========================================================
  // 親から渡ってくる値
  // =========================================================
  props: {
    propositions: { type: Array, required: true },
    idPrefix: { type: String, default: '' },
  },
  // =========================================================
  // メソッド
  // =========================================================
  methods: {
    /**
     * 親にお気に入り変更情報を伝える
     *
     * @param id 案件ID
     * @param liked お気に入り状態
     */
    like({ id, liked }) {
      this.$emit('like', {
        id: id,
        liked: liked,
      })
    },
  },
}
</script>
