<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TiltIsBack extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roofs', function (Blueprint $table) {
            $table->dropColumn('type_id');
            $table->dropColumn('slope');
            $table->dropColumn('ground_square_area');
            $table->dropColumn('occupancy_rate');
            $table->dropColumn('south_orientation');
            $table->dropColumn('inverter_location');
            $table->dropColumn('inverter_distance');
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
        });

        Schema::create('tilts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('roof_id');
            $table->integer('type_id');
            $table->integer('slope')->nullable();
            $table->integer('ground_square_area')->nullable();
            $table->integer('occupancy_rate')->nullable();
            $table->integer('south_orientation')->nullable();
            $table->string('inverter_location')->nullable();
            $table->integer('inverter_distance')->nullable();
            $table->float('latitude', 12, 10)->nullable();
            $table->float('longitude', 12, 10)->nullable();
            $table->timestamps();
        });

        Schema::rename('roof_types', 'tilt_types');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tilts');

        Schema::table('roofs', function (Blueprint $table) {
            $table->integer('type_id');
            $table->integer('slope')->nullable();
            $table->integer('ground_square_area')->nullable();
            $table->integer('occupancy_rate')->nullable();
            $table->integer('south_orientation')->nullable();
            $table->string('inverter_location')->nullable();
            $table->integer('inverter_distance')->nullable();
            $table->float('latitude', 12, 10)->nullable();
            $table->float('longitude', 12, 10)->nullable();
        });

        Schema::rename('tilt_types', 'roof_types');
    }
}
