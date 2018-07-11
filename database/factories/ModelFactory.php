<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

// $factory->define(App\User::class, function (Faker\Generator $faker) {
//     return [
//         'name' => $faker->name,
//         'email' => $faker->email,
//     ];
// });

$factory->define(App\Models\StationSell::class, function (Faker\Generator $faker) {
    return [
        'price' => $faker->price,
        'quantity' => $faker->quantity,
        'type_id' => rand(1,10),
    ];
});

$factory->define(App\Models\Type::class, function (Faker\Generator $faker) {
    return [
        'type' => str_random(6),
    ];
});
