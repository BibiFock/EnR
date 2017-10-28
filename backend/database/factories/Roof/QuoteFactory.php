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

$factory->define(App\Quote::class, function (Faker\Generator $faker) {
    return [
        'roof_id' => function() {
            return factory(App\Roof::class)->create()->id;
        },
        'total_off' => $faker->randomFloat(2, 5),
        'roi_off' => $faker->randomFloat(2, 5),
        'total' => $faker->randomFloat(2, 5),
        'roi' => $faker->randomFloat(2, 5),
        'panel_type' => $faker->word,
        'inverter_type' => $faker->word,
        'guarantee' => $faker->words,
        'certifications' => $faker->sentences,
        'remarks' => $faker->sentences,
    ];
});
