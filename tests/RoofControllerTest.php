<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

use App\Roof;
use App\Roof\Tilt;

class RoofControllerTest extends ApiCase
{
    use DatabaseTransactions;

    public function testList()
    {
        $roof = factory($this->getFactoryClass())->create();
        $tilt = factory(Tilt::class)->create([
            'roof_id' => $roof->id
        ]);

        $response = $this->actingAs($this->user)
            ->call('GET', $this->getUrl());
        $this->assertEquals(200, $response->status());

        $this->actingAs($this->user)
            ->get($this->getUrl())
            ->seeJsonStructure([
                $this->getBaseStructure()
            ]);

        $roof->forceDelete();
        $tilt->forceDelete();
    }

    public function testGetRoof()
    {
        $tilt = factory(Tilt::class)->create();

        $this->actingAs($this->user)->get(
             $this->getUrl() . $tilt->roof->id
        )->seeJsonStructure($this->getBaseStructure());

        $tilt->roof->forceDelete();

        $response = $this->actingAs($this->user)->call(
            'GET',
            $this->getUrl() . $tilt->roof->id
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

        $baseStructure = $this->getBaseStructure();
        unset($baseStructure['tilts']);

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
        $this->seeJsonStructure($baseStructure, $response->getData(true));

        // structure full -> should be fine
        $params['owner']['contact'] = $roof->owner->contact->getAttributes();
        $response = $this->actingAs($this->user)
            ->call('POST', $this->getUrl(), $params);
        $this->assertEquals( 200, $response->status());
        $this->seeJsonStructure($baseStructure, $response->getData(true));

        unset($params['owner']['contact']['first_name']);

         // structur full withtout contact first name
        $response = $this->actingAs($this->user)
            ->call('POST', $this->getUrl(), $params);
        $this->assertEquals( 200, $response->status());
        $this->seeJsonStructure($baseStructure, $response->getData(true));
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
        $this->seeJsonStructure(['owner.name'], $response->getData(true));
    }

    public function testAddRoof()
    {
        $tilt = factory(App\Roof\Tilt::class)->make();
        // roof
        $params = $tilt->roof->getAttributes();
        $params['erp'] = (boolean) $params['erp'];
        $params['perimeter_abf'] = (boolean) $params['perimeter_abf'];
        unset($params['id']);
        unset($params['owner_id']);
        $resultRoof = $params;
        // owner
        $params['owner'] = $tilt->roof->owner->getAttributes();
        unset($params['owner']['id']);
        unset($params['owner']['name']);
        unset($params['owner']['contact_id']);
        $resultOwner = $params['owner'];
        // contact
        $params['owner']['contact'] = $tilt->roof->owner->contact->getAttributes();
        unset($params['owner']['contact']['id']);
        $resultContact = $params['owner']['contact'];
        $resultOwner['name'] = $tilt->roof->owner->contact->first_name . ' '
            . $tilt->roof->owner->contact->last_name;

        $params['tilts'] = array($tilt->getAttributes());
        foreach ($params['tilts'] as &$t) {
            unset($t['id']);
            unset($t['roof_id']);
        }
        unset($t);
        $resultTilts = $params['tilts'][0];

        $this->actingAs($this->user)
            ->post($this->getUrl(), $params)
            ->seeJsonStructure($this->getBaseStructure())
            ->seeJson($resultRoof)
            ->seeJson($resultOwner)
            ->seeJson($resultContact)
            ->seeJson($resultTilts);
    }

    public function testUpdateRoof()
    {
        // toit de référence
        $roof = factory($this->getFactoryClass())->create();
        $tilt = factory(App\Roof\Tilt::class)->create(['roof_id' => $roof->id]);
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

        $tilt = factory(App\Roof\Tilt::class)->make([
            'id' => $tilt->id,
            'roof_id' => $roof->id
        ]);
        $datas['tilts'] = array($tilt->getAttributes());

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
            ->seeJson($owner)
            ->seeJson($datas['tilts'][0]);
        $roof->refresh();

        $this->assertEquals(1, $roof->tilts()->count());

        $roof->forceDelete();
    }

    public function testAddTilts()
    {
        $tilt = factory(App\Roof\Tilt::class)->create();
        $newTilt = factory(App\Roof\Tilt::class)->make([
            'roof_id' => $tilt->roof_id
        ]);

        $params = $tilt->roof->getAttributes();
        $params['erp'] = (boolean) $params['erp'];
        $params['perimeter_abf'] = (boolean) $params['perimeter_abf'];

        $datas = $params;
        $datas['tilts'] = array(
            $tilt->getAttributes(),
            $newTilt->getAttributes()
        );

        $this->actingAs($this->user)
            ->put(
                $this->getUrl() . $tilt->roof->id,
                $datas
            )->seeJson($params)
            ->seeJson($datas['tilts'][0])
            ->seeJson($datas['tilts'][1]);
        $tilt->roof->refresh();

        $this->assertEquals(2, $tilt->roof->tilts()->count());

        $tilt->roof->forceDelete();
    }

    public function testUpdateTilts()
    {
        $tilt = factory(App\Roof\Tilt::class)->create();
        $newTilt = factory(App\Roof\Tilt::class)->create([
            'roof_id' => $tilt->roof_id
        ]);
        $newTilt = factory(App\Roof\Tilt::class)->make([
            'id' => $newTilt->id,
            'roof_id' => $tilt->roof_id
        ]);

        $params = $tilt->roof->getAttributes();
        $params['erp'] = (boolean) $params['erp'];
        $params['perimeter_abf'] = (boolean) $params['perimeter_abf'];

        $datas = $params;
        $datas['tilts'] = array(
            $tilt->getAttributes(),
            $newTilt->getAttributes()
        );

        $this->actingAs($this->user)
            ->put(
                $this->getUrl() . $tilt->roof->id,
                $datas
            )->seeJson($params)
            ->seeJson($datas['tilts'][0])
            ->seeJson($datas['tilts'][1]);
        $tilt->roof->refresh();

        $this->assertEquals(2, $tilt->roof->tilts()->count());

        $tilt->roof->forceDelete();
    }

    public function testDeleteTilts()
    {

        $tilt = factory(App\Roof\Tilt::class)->create();
        $newTilt = factory(App\Roof\Tilt::class)->create([
            'roof_id' => $tilt->roof_id
        ]);

        $params = $tilt->roof->getAttributes();
        $params['erp'] = (boolean) $params['erp'];
        $params['perimeter_abf'] = (boolean) $params['perimeter_abf'];

        $datas = $params;
        $datas['tilts'] = array(
            $tilt->getAttributes()
        );

        $missingDatas = $newTilt->getAttributes();
        unset($missingDatas['created_at']);

        $this->actingAs($this->user)
            ->put(
                $this->getUrl() . $tilt->roof->id,
                $datas
            )->seeJson($params)
            ->seeJson($datas['tilts'][0]);

        $tilt->roof->refresh();

        $this->assertEquals(1, $tilt->roof->tilts()->count());

        $tilt->roof->forceDelete();
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
        $struct['tilts'] = [( new App\Roof\Tilt() )->getFillable()];

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
