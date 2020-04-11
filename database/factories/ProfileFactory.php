<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Profile::class, function (Faker $faker) {
    static $user_id;

    return [
        'user_id' => function () use ($user_id) {
            return $user_id ?? factory(App\Models\User::class)->create()->id;
        },
        'bio'        => $faker->paragraph(),
        'image'      => $faker->imageUrl(600, 800, 'cats'),
        'created_at' => $faker->datetime(),
        'updated_at' => $faker->datetime(),
    ];
});
