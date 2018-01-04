<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

// $router->get('/', function () use ($router) {
    // return $router->app->version();
// });

$router->group(
    ['prefix' => 'api'],
    function () use ($router) {

        $router->post('/auth/login', 'AuthController@loginPost');

        $router->get('/users', 'UserController@index');
        // autocomplete
        $router->get('map/search', 'MapSearchController@index');

        $router->group(
            ['middleware' => 'auth'],
            function() use ($router) {
                $genericId = '{id:[0-9]+}';

                // getters
                $router->get('structures', 'StructureController@index');
                $router->get('structures/' . $genericId, 'StructureController@get');
                $router->get('structure_types', 'StructureTypeController@index');
                $router->get('departments', 'DepartmentController@index');

                $router->get('export', 'ExportController@index');

                $router->get('roof/purchase_categories', 'RoofPurchaseCategoryController@index');
                $router->get('roof/south_orientations', 'RoofSouthOrientationController@index');
                $router->get('roof/types', 'RoofTypeController@index');
                $router->get('roof/probabilities', 'RoofController@getProbabilities');

                $router->get('roofs', 'RoofController@index');
                $router->get('roofs/' . $genericId, 'RoofController@getRoof');
                $router->get(
                    'roofs/' . $genericId . '/historical',
                    'RoofHistoricalController@index'
                );

                // modifier
                $router->post('roofs', 'RoofController@addRoof');
                $router->put('roofs/' . $genericId, 'RoofController@updateRoof');
            }
        );
    }
);
