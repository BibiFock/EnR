<?php

use App\Department;

use App\Roof;
use App\Roof\Historical;

class RoofHistoricalControllerTest extends ApiCase
{
    protected $roof = null;

    public function setUp()
    {
        parent::setUp();
        $this->roof = factory('App\Roof')->create();
        $this->roof->saveState($this->user->id);
    }

    public function tearDOWN()
    {
        $this->roof->forceDelete();
        parent::tearDOWN();
    }

    public function testStateInsert()
    {
        $nbState = $this->getNbState();

        $response = $this->actingAs($this->user)
            ->call(
                'PUT',
                '/api/roofs/' . $this->roof->id,
                factory('App\Roof')->make()->getAttributes()
            );
        $this->assertEquals( 200, $response->status());

        $this->assertEquals( $nbState+1, $this->getNbState());
    }

    protected function getUrl()
    {
        return '/api/roofs/' . $this->roof->id . '/historical';
    }

    protected function getBaseStructure()
    {
        $struct = ( new Historical() )->getFillable();
        $struct['user'] = $this->user->getVisible();
        return $struct;
    }

    protected function getFactoryClass()
    {
        return false;
    }

    protected function getNbState()
    {
        $response = $this->actingAs($this->user)
            ->call('GET', $this->getUrl());
        $json = $response->getContent();
        $list = json_decode($json, true);

        return count($list);
    }
}
