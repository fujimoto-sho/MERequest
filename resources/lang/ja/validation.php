<?php

return [

    /*
      |--------------------------------------------------------------------------
      | Validation Language Lines
      | 検証言語
      |--------------------------------------------------------------------------
      |
      | The following language lines contain the default error messages used by
      | the validator class. Some of these rules have multiple versions such
      | as the size rules. Feel free to tweak each of these messages here.
      |
      | 次の言語行には、バリデータークラスで使用されるデフォルトのエラーメッセージが含まれています。
      | これらの規則の中には、サイズ規則などの複数のバージョンがあります。
      | これらのメッセージのそれぞれをここで微調整してください。
    */

    'accepted'             => '未承認です',
    'active_url'           => '有効なURLではありません',
    'after'                => ':date より後の日付にしてください',
    'after_or_equal'       => ':date 以降の日付にしてください',
    'alpha'                => '英字のみ有効です',
    'alpha_dash'           => '「英字」「数字」「-(ダッシュ)」「_(下線)」のみ有効です',
    'alpha_num'            => '「英字」「数字」のみ有効です',
    'array'                => '配列タイプのみ有効です',
    'before'               => ':date より前の日付にしてください',
    'before_or_equal'      => ':date 以前の日付にしてください',
    'between'              => [
        'numeric' => ':min ～ :max までの数値まで有効です',
        'file'    => ':min ～ :max キロバイトまで有効です',
        'string'  => ':min ～ :max 文字まで有効です',
        'array'   => ':min ～ :max 個まで有効です',
    ],
    'boolean'              => '値は true もしくは false のみ有効です',
    'confirmed'            => '確認用と一致させてください',
    'date'                 => '有効な日付形式にしてください',
    'date_format'          => ':format 書式と一致させてください',
    'different'            => ':other と違うものにしてください',
    'digits'               => ':digits 桁のみ有効です',
    'digits_between'       => ':min ～ :max 桁のみ有効です',
    'dimensions'           => 'ルールに合致する画像サイズのみ有効です',
    'distinct'             => '重複している値があります',
    'email'                => 'このメールアドレスは使用できません',
    'exists'               => '無効な値です',
    'file'                 => 'アップロード出来ないファイルです',
    'filled'               => '値を入力してください',
    'gt'                   => [
        'numeric' => ':value より大きい必要があります。',
        'file'    => ':value キロバイトより大きい必要があります。',
        'string'  => ':value 文字より多い必要があります。',
        'array'   => ':value 個より多くの項目が必要です。',
    ],
    'gte'                  => [
        'numeric' => ':value 以上である必要があります。',
        'file'    => ':value キロバイト以上である必要があります。',
        'string'  => ':value 文字以上である必要があります。',
        'array'   => ':value 個以上の項目が必要です。',
    ],
    'image'                => '画像は「jpg」「png」「bmp」「gif」「svg」のみ有効です',
    'in'                   => '無効な値です',
    'in_array'             => ':other と一致する必要があります',
    'integer'              => '整数のみ有効です',
    'ip'                   => 'IPアドレスの書式のみ有効です',
    'ipv4'                 => 'IPアドレス(IPv4)の書式のみ有効です',
    'ipv6'                 => 'IPアドレス(IPv6)の書式のみ有効です',
    'json'                 => '正しいJSON文字列のみ有効です',
    'lt'                   => [
        'numeric' => ':value 未満である必要があります。',
        'file'    => ':value キロバイト未満である必要があります。',
        'string'  => ':value 文字未満である必要があります。',
        'array'   => ':value 未満の項目を持つ必要があります。',
    ],
    'lte'                  => [
        'numeric' => ':value 以下である必要があります。',
        'file'    => ':value キロバイト以下である必要があります。',
        'string'  => ':value 文字以下である必要があります。',
        'array'   => ':value 以上の項目を持つ必要があります。',
    ],
    'max'                  => [
        'numeric' => ':max 以下のみ有効です',
        'file'    => ':max KB以下のファイルのみ有効です',
        'string'  => ':max 文字以下のみ有効です',
        'array'   => ':max 個以下のみ有効です',
    ],
    'mimes'                => ':values タイプのみ有効です',
    'mimetypes'            => ':values タイプのみ有効です',
    'min'                  => [
        'numeric' => ':min 以上のみ有効です',
        'file'    => ':min KB以上のファイルのみ有効です',
        'string'  => ':min 文字以上のみ有効です',
        'array'   => ':min 個以上のみ有効です',
    ],
    'not_in'               => '無効な値です',
    'not_regex'            => '無効な値です',
    'numeric'              => '数字のみ有効です',
    'present'              => '存在しません',
    'regex'                => '無効な値です',
    'required'             => '必須入力です',
    'required_if'          => ':other が :value には必須です',
    'required_unless'      => ':other が :values でなければ必須です',
    'required_with'        => ':values が入力されている場合は必須です',
    'required_with_all'    => ':values が入力されている場合は必須です',
    'required_without'     => ':values が入力されていない場合は必須です',
    'required_without_all' => ':values が入力されていない場合は必須です',
    'same'                 => ':other と同じ場合のみ有効です',
    'size'                 => [
        'numeric' => ':size のみ有効です',
        'file'    => ':size KBのみ有効です',
        'string'  => ':size 文字のみ有効です',
        'array'   => ':size 個のみ有効です',
    ],
    'string'               => '文字列のみ有効です',
    'timezone'             => '正しいタイムゾーンのみ有効です',
    'unique'               => '既に存在します',
    'uploaded'             => 'アップロードに失敗しました',
    'url'                  => '正しいURL書式のみ有効です',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    | カスタム検証言語
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    | ここでは、行に名前を付けるために "attribute.rule"という規則を使って属性のカスタム
    | 検証メッセージを指定することができます。 これにより、特定の属性ルールに対して特定の
    | カスタム言語行をすばやく指定できます。
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
      |--------------------------------------------------------------------------
      | Custom Validation Attributes
      | カスタム検証属性
      |--------------------------------------------------------------------------
      |
      | The following language lines are used to swap attribute place-holders
      | with something more reader friendly such as E-Mail Address instead
      | of "email". This simply helps us make messages a little cleaner.
      |
      | 次の言語行は、属性プレースホルダを「email」ではなく「E-Mail Address」などの
      | 読みやすいものと交換するために使用されます。
      |
    */

    'attributes' => [],

];
