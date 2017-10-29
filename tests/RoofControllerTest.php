<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class RoofControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testRoofsList()
    {
        $this->get('/api/roofs')
            ->seeJsonStructure([[
                'id', 'propability', 'square_area', 'power_max', 'power_min', 'erp',
                'building_size', 'perimeter_abf', 'remarks',
                'inverter_location', 'inverter_distance', 'street',
                'zip', 'city', 'latitude', 'longitude',
                // relations
                'owner_id', 'structure_id', 'south_orientation_id',
                'purchase_category_id', 'type_id', 'tilt_id', 'department_id',
                // other infos
                // structure
                'structure' => [ 'id', 'name', 'contact_id', 'type_id' ],
                'owner' => [ 'id', 'first_name', 'last_name', 'phone', 'email' ]
            ]]);
    }

    public function testRoof()
    {
        $this->get('/api/roofs/1')
            ->seeJsonStructure([
                'id', 'propability', 'square_area', 'power_max', 'power_min', 'erp',
                'building_size', 'perimeter_abf', 'remarks',
                'inverter_location', 'inverter_distance', 'street',
                'zip', 'city', 'latitude', 'longitude',
                // relations
                'owner_id', 'structure_id', 'south_orientation_id',
                'purchase_category_id', 'type_id', 'tilt_id', 'department_id',
                // other infos
                // structure
                'structure' => [ 'id', 'name', 'contact_id', 'type_id' ],
                'owner' => [ 'id', 'first_name', 'last_name', 'phone', 'email' ]
            ]);
    }
}
