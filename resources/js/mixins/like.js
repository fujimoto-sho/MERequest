// =========================================================
// 案件お気に入りのミックスイン
// =========================================================
export default {
  // =========================================================
  // メソッド
  // =========================================================
  methods: {
    /**
     * 子から渡ってきたお気に入りの 登録/削除 判定
     *
     * 現在お気に入り登録されていたらお気に入り削除、以外は登録する
     *
     * @param id 案件ID
     * @param liked お気に入り情報
     */
    onLikeClick({ id, liked }) {
      // ログインしていなかったらなにもしない
      if (!this.$store.getters['auth/check']) {
        return false
      }

      if (liked) {
        // お気に入り削除
        this.unlike(id)
      } else {
        // お気に入り登録
        this.like(id)
      }
    },
    /**
     * 案件一覧のカードに表示するお気に入り情報を変更し、返却する
     *
     * お気に入り数・自分がお気に入りしているかの情報を変更する
     *
     * @param propositions 案件一覧
     * @param propositionId お気に入り情報変更対象の案件ID
     * @param isRegister 登録か
     */
    async setLikeInfo(propositions, propositionId, isRegister) {
      // 表示している案件がない場合はそのまま返す
      if (propositions === null) {
        return null
      }

      // お気に入り情報を変更し、返却する
      return propositions.map(proposition => {
        // お気に入り情報変更対象の案件の場合のみ、変更する
        if (proposition.id === propositionId) {
          // セットするお気に入り数
          const setLikeCount = isRegister ? proposition.likes_count + 1 : proposition.likes_count - 1
          // お気に入り数を変更
          this.$set(proposition, 'likes_count', setLikeCount)
          // 自分がお気に入りしているかの情報を変更
          this.$set(proposition, 'is_like', isRegister)
        }
        return proposition
      })
    },
  },
}
