<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

use App\Roof;

class RoofControllerTest extends ApiCase
{
    use DatabaseTransactions;

    protected $roofStructure = [];

    public function testGetRoof()
    {
        $roof = factory($this->getFactoryClass())->make();

        $roof->save();
        $this->actingAs($this->user)->get(
             $this->getUrl() . $roof->id
        )->seeJsonStructure($this->roofStructure);

        $roof->forceDelete();

        $response = $this->actingAs($this->user)->call(
            'GET',
            $this->getUrl() . $roof->id
        );
        $this->assertEquals( 404, $response->status());
    }

    public function testValidatorRoof()
    {
        $roof = factory($this->getFactoryClass())->make();
        $params = $roof->getAttributes();

        unset($params['name']);

        $response = $this->actingAs($this->user)
            ->call('POST', $this->getUrl(), $params);
        $this->assertEquals( 400, $response->status());
    }

    public function testValidatorOwner()
    {
        $roof = factory($this->getFactoryClass())->make();
        $params = $roof->getAttributes();

        // missing owner.type_id
        $params['owner'] = [
            'contact' => $roof->owner->contact->getAttributes()
        ];

        $response = $this->actingAs($this->user)
            ->call('POST', $this->getUrl(), $params);
        $this->assertEquals( 400, $response->status());
        $this->seeJsonStructure(['owner.type_id'], $response->getData(true));

        // without contact
        $params['owner'] = $roof->owner->getAttributes();
        $params['owner'] = $roof->owner->getAttributes();
        $response = $this->actingAs($this->user)
            ->call('POST', $this->getUrl(), $params);
        $this->assertEquals( 200, $response->status());
        $this->seeJsonStructure($this->roofStructure, $response->getData(true));

        // structure full -> should be fine
        $params['owner']['contact'] = $roof->owner->contact->getAttributes();
        $response = $this->actingAs($this->user)
            ->call('POST', $this->getUrl(), $params);
        $this->assertEquals( 200, $response->status());
        $this->seeJsonStructure($this->roofStructure, $response->getData(true));

        unset($params['owner']['contact']['first_name']);

         // structur full withtout contact first name
        $response = $this->actingAs($this->user)
            ->call('POST', $this->getUrl(), $params);
        $this->assertEquals( 200, $response->status());
        $this->seeJsonStructure($this->roofStructure, $response->getData(true));
        $this->seeJson(['name' => $roof->owner->contact->last_name], $response->getData(true));

         // structur full withtout contact last name
        $params['owner']['contact']['first_name'] = $roof->owner->contact->first_name;
        unset($params['owner']['contact']['last_name']);
        $response = $this->actingAs($this->user)
            ->call('POST', $this->getUrl(), $params);
        $this->assertEquals( 200, $response->status());
        $this->seeJson(['name' => $roof->owner->contact->first_name], $response->getData(true));

        // without first and last name
        unset($params['owner']['contact']['first_name']);
        unset($params['owner']['contact']['last_name']);

        $response = $this->actingAs($this->user)
            ->call('POST', $this->getUrl(), $params);
        $this->assertEquals( 400, $response->status());
        $this->seeJsonStructure(['owner.*_name'], $response->getData(true));

    }

    public function testAddRoof()
    {
        $roof = factory($this->getFactoryClass())->make();
        $params = $roof->getAttributes();
        $struct = array_merge(array_keys($params), ['id']);

        $resultRoof = $params;
        $roof->owner->name = $roof->owner->contact->first_name . ' '
            . $roof->owner->contact->last_name;
        $resultOwner = $params['owner'] = $roof->owner->getAttributes();

        $params['owner']['contact'] = $roof->owner->contact->getAttributes();

        // test creation
        $response = $this->actingAs($this->user)
            ->post($this->getUrl(), $params)
            ->seeJsonStructure($struct)
            ->seeJson($resultRoof)
            ->seeJson($resultOwner);
    }

    public function testUpdateRoof()
    {
        // toit de référence
        $roof = factory($this->getFactoryClass())->create();
        // on regénère un jeu de données complet
        $params = factory($this->getFactoryClass())->make()->getAttributes();
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
            $this->getUrl() . ($roof->id . '1111111'),
            $datas
        );

        $this->assertEquals(
            404,
            $response->status()
        );

        $this->actingAs($this->user)
            ->put(
                $this->getUrl() . $roof->id,
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

    protected function getBaseStructure()
    {
        $struct = ( new Roof() )->getFillable();
        $struct['structure'] = ( new App\Structure() )->getFillable();
        $struct['owner'] = ( new App\Structure() )->getFillable();

        return $struct;
    }

    protected function getUrl()
    {
        return '/api/roofs/';
    }

    protected function getFactoryClass()
    {
        return Roof::class;
    }

}
