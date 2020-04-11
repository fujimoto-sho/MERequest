<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Proposition::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'title' => $faker->sentence(),
        'type'  => $type = $faker->numberBetween(0, 1),
        // 単発の場合最大6桁（千円単位）、レベニューシェアの場合最大3桁
        'number_min'       => $number_min = $type === 0 ? $faker->numberBetween(1, 999) * 1000 : $faker->numberBetween(0, 100),
        'number_max'       => $type === 0 ? $faker->numberBetween($number_min/1000, 999) * 1000 : $faker->numberBetween($number_min, 100),
        'recruiting_count' => $faker->numberBetween(1, 999),
        'content'          => $faker->paragraph(),
        'status'           => $faker->numberBetween(0, 1),
    ];
});
