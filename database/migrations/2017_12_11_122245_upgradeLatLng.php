<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpgradeLatLng extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roofs', function (Blueprint $table) {
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
        });

        Schema::table('roofs', function (Blueprint $table) {
            $table->float('latitude', 23, 20)->nullable()->after('department_id');
            $table->float('longitude', 23, 20)->nullable()->after('latitude');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roofs', function (Blueprint $table) {
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
        });

        Schema::table('roofs', function (Blueprint $table) {
            $table->float('latitude', 12, 10)->nullable()->after('department_id');
            $table->float('longitude', 12, 10)->nullable()->after('latitude');
        });

    }
}
