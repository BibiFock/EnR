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

$factory->define(App\Roof\Tilt::class, function (Faker\Generator $faker) {
    return [
        'roof_id' => function() {
            return factory(App\Roof::class)->create()->id;
        },
        'name' => $faker->name,
        'type_id' => rand(1, 10),
        'slope' => $faker->randomNumber(2),
        'ground_square_area' => $faker->randomNumber(3),
        'occupancy_rate' => $faker->randomNumber(2),
        'south_orientation' => $faker->randomNumber(2),
        'inverter_location' => $faker->word() ,
        'inverter_distance' => $faker->randomNumber(4),
        'latitude' => $faker->randomFloat(4, 48,49),
        'longitude' => $faker->randomFloat(4, 2, 3),
    ];
});
