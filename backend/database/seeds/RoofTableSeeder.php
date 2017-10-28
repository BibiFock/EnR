<?php

use Illuminate\Database\Seeder;

class RoofTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Roof::class, 20)->create();
    }
}
