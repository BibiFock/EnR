<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

use App\Roof;

class RoofControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $roofStructure = [];

    public function setUp()
    {
        parent::setUp();
        $this->user = factory('App\User')->create();

        $this->roofStructure = ( new Roof() )->getFillable();
        $this->roofStructure['structure'] = ( new App\Structure() )->getFillable();
        $this->roofStructure['owner'] = ( new App\Structure() )->getFillable();
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

    public function testGetRoof()
    {
        $roof = factory('App\Roof')->make();

        $roof->save();
        $this->actingAs($this->user)->get(
            '/api/roofs/' . $roof->id
        )->seeJsonStructure($this->roofStructure);

        $roof->forceDelete();

        $response = $this->actingAs($this->user)->call(
            'GET',
            '/api/roofs/' . $roof->id
        );
        $this->assertEquals( 404, $response->status());
    }

    public function testAddRoof()
    {
        $roof = factory('App\Roof')->make();
        $params = $roof->getAttributes();
        $struct = array_merge(array_keys($params), ['id']);

        $roof->owner->name = $roof->owner->contact->first_name . ' '
            . $roof->owner->contact->last_name;
        $resultRoof = $params;

        $resultOwner = $params['owner'] = $roof->owner->getAttributes();
        $struc = array_keys($params['owner']);

        $params['owner']['contact'] = $roof->owner->contact->getAttributes();

        $response = $this->actingAs($this->user)
            ->post('/api/roofs', $params)
            ->seeJsonStructure($struct)
            ->seeJson($resultRoof)
            ->seeJson($resultOwner);
    }

    public function testUpdateRoof()
    {
        // toit de référence
        $roof = factory('App\Roof')->create();
        // on regénère un jeu de données complet
        $params = factory('App\Roof')->make()->getAttributes();
        // on utilise les mêmes ids histoires de voir si les datas sont bien modifiée
        $params['owner_id'] = $roof->owner_id;

        $owner = factory('App\Structure')->make([
            'contact_id' => $roof->owner->contact_id
        ])->getAttributes();

        $contact = factory('App\Contact')->make()->getAttributes();

        $datas = $params;
        $datas['owner'] = $owner;
        $datas['owner']['contact'] = $contact;

        $owner['name'] = $contact['first_name'] . ' ' . $contact['last_name'];

        // Test 404
        $response = $this->actingAs($this->user)->call(
            'PUT',
            '/api/roofs/' . ($roof->id . '1111111'),
            $datas
        );

        $this->assertEquals(
            404,
            $response->status()
        );

        $this->actingAs($this->user)
            ->put(
                '/api/roofs/' . $roof->id,
                $datas
            )->seeJson($params)
            ->seeJson($owner);

        $roof->forceDelete();
    }

    public function testProbabilitiesList()
    {
        $response = $this->actingAs($this->user)
            ->call('GET', '/api/roof/probabilities');

        $this->assertEquals(200, $response->status());
     }

}
