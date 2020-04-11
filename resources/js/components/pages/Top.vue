<!--==========================================================================-->
<!-- トップページ -->
<!--==========================================================================-->
<template>
  <div>
    <div class="p-top__intro">
      <section class="p-top__section">
        <h2 class="p-top__intro-title">どこよりも簡単に動画編集を依頼してみませんか？</h2>
        <p class="p-top__intro-content">
          動画編集依頼サービス「MERequest」なら、<br />
          どこよりも簡単に動画編集を依頼することができます！
        </p>
        <vue-button @click="moveRegister" text="会員登録（無料）" />
      </section>
    </div>

    <div class="p-top__concept">
      <section class="p-top__section">
        <h2 class="p-top__concept-title">
          <span class="p-top__concept-title--under">「MERequest」のコンセプト</span>
        </h2>
        <p class="p-top__concept-content">
          現在、<span class="u-fw-bold">クラウドソーシングサービス</span>といった、<br />
          インターネットを介して、不特定多数の人に仕事を委託するサービスが流行っています。
        </p>
        <p class="p-top__concept-content">
          ですが、現在よく使われているサービスは、<br />
          オプションや入力項目が色々あり、<span class="u-fw-bold">わかりにくい</span>です。<br />
        </p>
        <p class="p-top__concept-content">
          慣れていないと、ハードルが高く感じられ、 最初の一歩が踏み出せずにいる方も多くいるのではないでしょうか？
        </p>
        <p class="p-top__concept-content">
          <span class="u-fs-l u-fw-bold">そこを解決するのがMERequestです。</span>
        </p>
        <p class="p-top__concept-content">
          このMERequestは<span class="u-fw-bold">「誰でも簡単に」</span>をコンセプトに開発されました。<br />
          そのため、他のサービスとは比べものにならないくらい簡単に動画編集を依頼することが出来ます！
        </p>
        <p class="p-top__concept-content">
          あなたも気軽に動画編集案件を依頼、または応募してみませんか？
        </p>
      </section>
    </div>

    <div>
      <section class="p-top__section">
        <p class="u-fw-bold">簡単・気軽に動画編集案件を依頼、または応募してみましょう！</p>
        <vue-button @click="moveRegister" text="会員登録（無料）" class="u-mt30" />
      </section>
    </div>
  </div>
</template>

<script>
export default {
  // =========================================================
  // タブに表示されるタイトル
  // =========================================================
  title: 'TOP',
  // =========================================================
  // メソッド
  // =========================================================
  methods: {
    /**
     * 会員登録画面に遷移する
     */
    moveRegister() {
      this.$router.push('/register')
    },
  },
}
</script>
