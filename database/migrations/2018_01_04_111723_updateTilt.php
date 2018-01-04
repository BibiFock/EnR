<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 *  Suppression de la liaison tilt dans la bd
 *
 */
class UpdateTilt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roofs', function (Blueprint $table) {
            $table->dropColumn('tilt_id');

            $table->integer('slope')->nullable()->after('type_id');
            $table->integer('ground_square_area')->nullable()->after('slope');
            $table->integer('occupancy_rate')->nullable()->after('ground_square_area');
        });

        Schema::dropIfExists('tilts');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roofs', function (Blueprint $table) {
            $table->dropColumn('slope');
            $table->dropColumn('ground_square_area');
            $table->dropColumn('occupancy_rate');
            $table->integer('tilt_id')->nullable()->after('type_id');
        });

        Schema::create('tilts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->timestamps();
        });

        DB::table('tilts')->insert(
            array('name' => '35° optimale'),
            array('name' => '> 25°/40°<'),
            array('name' => '0° (à plat) à 10°'),
            array('name' => '90° verticale (déconseillée)'),
            array('name' => '> 10°/25°<')
        );
    }
}
