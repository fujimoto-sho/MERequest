<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Message::class, function (Faker $faker) {
    static $proposition_id;
    static $user_id;

    return [
        'proposition_id' => function () use ($proposition_id) {
            return $proposition_id ?? factory(App\Models\Proposition::class)->create()->id;
        },
        'user_id' => function () use ($user_id) {
            return $user_id ?? factory(App\Models\User::class)->create()->id;
        },
        'message' => $faker->sentence,
    ];
});
