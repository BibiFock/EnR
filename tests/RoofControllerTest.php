<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class RoofControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->user = factory('App\User')->create();
    }

    public function tearDOWN()
    {
        $this->user->forceDelete();
        parent::tearDOWN();
    }

    public function testNeedToken()
    {
        $response = $this->call('GET', '/api/roofs');

        $this->assertEquals(401, $response->status());
    }

    public function testRoofsList()
    {
        $this->actingAs($this->user)
            ->get('/api/roofs')
            ->seeJsonStructure([[
                'id', 'name', 'probability', 'square_area',
                'power_max', 'power_min', 'erp',
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
}
