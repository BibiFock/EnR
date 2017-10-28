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

$factory->define(App\Structure::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'type_id' => rand(1,4),
        'contact_id' => function() {
            return factory(App\Contact::class)->create()->id;
        }
    ];
});
