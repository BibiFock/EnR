<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteDepartment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roofs', function (Blueprint $table) {
            $table->dropColumn('department_id');

        });

        Schema::dropIfExists('departments');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roofs', function (Blueprint $table) {
            $table->integer('department_id')->nullable();
        });

        Schema::create('departments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('zip', 3);
            $table->string('name', 150);
            $table->timestamps();
        });

        DB::table('departments')->insert(
            array('zip' => '01', 'name' => 'Ain'),
            array('zip' => '02', 'name' => 'Aisne'),
            array('zip' => '03', 'name' => 'Allier'),
            array('zip' => '05', 'name' => 'Hautes-Alpes'),
            array('zip' => '04', 'name' => 'Alpes-de-Haute-Provence'),
            array('zip' => '06', 'name' => 'Alpes-Maritimes'),
            array('zip' => '07', 'name' => 'Ardèche'),
            array('zip' => '08', 'name' => 'Ardennes'),
            array('zip' => '09', 'name' => 'Ariège'),
            array('zip' => '10', 'name' => 'Aube'),
            array('zip' => '11', 'name' => 'Aude'),
            array('zip' => '12', 'name' => 'Aveyron'),
            array('zip' => '13', 'name' => 'Bouches-du-Rhône'),
            array('zip' => '14', 'name' => 'Calvados'),
            array('zip' => '15', 'name' => 'Cantal'),
            array('zip' => '16', 'name' => 'Charente'),
            array('zip' => '17', 'name' => 'Charente-Maritime'),
            array('zip' => '18', 'name' => 'Cher'),
            array('zip' => '19', 'name' => 'Corrèze'),
            array('zip' => '2a', 'name' => 'Corse-du-sud'),
            array('zip' => '2b', 'name' => 'Haute-corse'),
            array('zip' => '21', 'name' => 'Côte-d\'or'),
            array('zip' => '22', 'name' => 'Côtes-d\'armor'),
            array('zip' => '23', 'name' => 'Creuse'),
            array('zip' => '24', 'name' => 'Dordogne'),
            array('zip' => '25', 'name' => 'Doubs'),
            array('zip' => '26', 'name' => 'Drôme'),
            array('zip' => '27', 'name' => 'Eure'),
            array('zip' => '28', 'name' => 'Eure-et-Loir'),
            array('zip' => '29', 'name' => 'Finistère'),
            array('zip' => '30', 'name' => 'Gard'),
            array('zip' => '31', 'name' => 'Haute-Garonne'),
            array('zip' => '32', 'name' => 'Gers'),
            array('zip' => '33', 'name' => 'Gironde'),
            array('zip' => '34', 'name' => 'Hérault'),
            array('zip' => '35', 'name' => 'Ile-et-Vilaine'),
            array('zip' => '36', 'name' => 'Indre'),
            array('zip' => '37', 'name' => 'Indre-et-Loire'),
            array('zip' => '38', 'name' => 'Isère'),
            array('zip' => '39', 'name' => 'Jura'),
            array('zip' => '40', 'name' => 'Landes'),
            array('zip' => '41', 'name' => 'Loir-et-Cher'),
            array('zip' => '42', 'name' => 'Loire'),
            array('zip' => '43', 'name' => 'Haute-Loire'),
            array('zip' => '44', 'name' => 'Loire-Atlantique'),
            array('zip' => '45', 'name' => 'Loiret'),
            array('zip' => '46', 'name' => 'Lot'),
            array('zip' => '47', 'name' => 'Lot-et-Garonne'),
            array('zip' => '48', 'name' => 'Lozère'),
            array('zip' => '49', 'name' => 'Maine-et-Loire'),
            array('zip' => '50', 'name' => 'Manche'),
            array('zip' => '51', 'name' => 'Marne'),
            array('zip' => '52', 'name' => 'Haute-Marne'),
            array('zip' => '53', 'name' => 'Mayenne'),
            array('zip' => '54', 'name' => 'Meurthe-et-Moselle'),
            array('zip' => '55', 'name' => 'Meuse'),
            array('zip' => '56', 'name' => 'Morbihan'),
            array('zip' => '57', 'name' => 'Moselle'),
            array('zip' => '58', 'name' => 'Nièvre'),
            array('zip' => '59', 'name' => 'Nord'),
            array('zip' => '60', 'name' => 'Oise'),
            array('zip' => '61', 'name' => 'Orne'),
            array('zip' => '62', 'name' => 'Pas-de-Calais'),
            array('zip' => '63', 'name' => 'Puy-de-Dôme'),
            array('zip' => '64', 'name' => 'Pyrénées-Atlantiques'),
            array('zip' => '65', 'name' => 'Hautes-Pyrénées'),
            array('zip' => '66', 'name' => 'Pyrénées-Orientales'),
            array('zip' => '67', 'name' => 'Bas-Rhin'),
            array('zip' => '68', 'name' => 'Haut-Rhin'),
            array('zip' => '69', 'name' => 'Rhône'),
            array('zip' => '70', 'name' => 'Haute-Saône'),
            array('zip' => '71', 'name' => 'Saône-et-Loire'),
            array('zip' => '72', 'name' => 'Sarthe'),
            array('zip' => '73', 'name' => 'Savoie'),
            array('zip' => '74', 'name' => 'Haute-Savoie'),
            array('zip' => '75', 'name' => 'Paris'),
            array('zip' => '76', 'name' => 'Seine-Maritime'),
            array('zip' => '77', 'name' => 'Seine-et-Marne'),
            array('zip' => '78', 'name' => 'Yvelines'),
            array('zip' => '79', 'name' => 'Deux-Sèvres'),
            array('zip' => '80', 'name' => 'Somme'),
            array('zip' => '81', 'name' => 'Tarn'),
            array('zip' => '82', 'name' => 'Tarn-et-Garonne'),
            array('zip' => '83', 'name' => 'Var'),
            array('zip' => '84', 'name' => 'Vaucluse'),
            array('zip' => '85', 'name' => 'Vendée'),
            array('zip' => '86', 'name' => 'Vienne'),
            array('zip' => '87', 'name' => 'Haute-Vienne'),
            array('zip' => '88', 'name' => 'Vosges'),
            array('zip' => '89', 'name' => 'Yonne'),
            array('zip' => '90', 'name' => 'Territoire de Belfort'),
            array('zip' => '91', 'name' => 'Essonne'),
            array('zip' => '92', 'name' => 'Hauts-de-Seine'),
            array('zip' => '93', 'name' => 'Seine-Saint-Denis'),
            array('zip' => '94', 'name' => 'Val-de-Marne'),
            array('zip' => '95', 'name' => 'Val-d\'oise'),
            array('zip' => '976', 'name' => 'Mayotte'),
            array('zip' => '971', 'name' => 'Guadeloupe'),
            array('zip' => '973', 'name' => 'Guyane'),
            array('zip' => '972', 'name' => 'Martinique'),
            array('zip' => '974', 'name' => 'Réunion')
        );
    }
}
