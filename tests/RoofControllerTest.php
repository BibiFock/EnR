<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

use App\Roof;

class RoofControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $roofStructure = [
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
    ];

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
            ->seeJsonStructure([$this->roofStructure]);
    }

    public function testAddRoof()
    {
        $roof = factory('App\Roof')->make();
        $params = $roof->getAttributes();
        $struct = array_merge(array_keys($params), ['id']);

        $response = $this->actingAs($this->user)
            ->post(
                '/api/roofs',
                $params
            )->seeJson($roof->getAttributes())
            ->seeJsonStructure($struct);
    }

    public function testUpdateRoof()
    {
        $roof = factory('App\Roof')->create();
        $params = factory('App\Roof')->make()->getAttributes();

        // Test 404
        $response = $this->actingAs($this->user)->call(
            'PUT',
            '/api/roofs/' . ($roof->id + 1),
            $params
        );

        $this->assertEquals(
            404,
            $response->status()
        );

        $this->actingAs($this->user)
            ->put(
                '/api/roofs/' . $roof->id,
                $params
            )->seeJson($params);

        $roof->forceDelete();
    }

    public function testProbabilitiesList()
    {
        $response = $this->actingAs($this->user)
            ->call('GET', '/api/roof/probabilities');

        $this->assertEquals(200, $response->status());
     }

}
