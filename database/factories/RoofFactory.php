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

$factory->define(App\Roof::class, function (Faker\Generator $faker) {
    $probabilities = App\Roof::PROBABILITIES;

    return [
        'name' => $faker->name,
        'probability' => $probabilities[array_rand($probabilities)],
        'structure_id' => function() {
            return factory(App\Structure::class)->create([
                'type_id' => 1 // structure initiatrice
            ])->id;
        },
        'square_area' => $faker->randomDigitNotNull,
        'power_max' => $faker->randomNumber(2),
        'power_min' => $faker->randomNumber(2),
        'purchase_category_id' => rand(1, 5),
        'erp' => $faker->boolean,
        'building_size' => $faker->randomNumber(3),
        'perimeter_abf' => $faker->boolean,
        'remarks' => $faker->sentence() ,
        'street' => $faker->streetAddress,
        'zip' => $faker->postcode,
        'city' => $faker->city,
        'owner_id' => function () {
            return factory(App\Structure::class)->create([
                'type_id' => rand(2, 7)
            ])->id;
        }
    ];
});
