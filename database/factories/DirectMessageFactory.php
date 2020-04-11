<?php

use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

$factory->define(App\Models\DirectMessage::class, function (Faker $faker) {
    static $applications_id;
    static $user_id;

    return [
        'application_id' => function () use ($applications_id) {
            if (isset($applications_id)) return $applications_id;

            $proposition = factory(App\Models\Proposition::class)->create();
            $proposition->applications()->attach(factory(App\Models\User::class)->create()->id);
            return DB::table('applications')
                ->orderBy('created_at', 'desc')
                ->first()
                ->id;
        },
        'user_id' => function () use ($user_id) {
            return $user_id ?? factory(App\Models\User::class)->create()->id;
        },
        'message' => $faker->text,
    ];
});
