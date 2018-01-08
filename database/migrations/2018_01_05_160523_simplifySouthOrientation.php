<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SimplifySouthOrientation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roofs', function (Blueprint $table) {
            $table->dropColumn('south_orientation_id');

            $table->integer('south_orientation')->nullable()->after('occupancy_rate');
        });

        Schema::dropIfExists('south_orientations');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roofs', function (Blueprint $table) {
            $table->dropColumn('south_orientation');
            $table->integer('south_orientation_id')->nullable()->after('occupancy_rate');
        });


        Schema::create('south_orientations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->timestamps();
        });

        DB::table('south_orientations')->insert(
            array('name' => '180° Sud'),
            array('name' => '+/-5°'),
            array('name' => '+/- 10°'),
            array('name' => '+/- 15°'),
            array('name' => '> 20°')
        );
    }
}
