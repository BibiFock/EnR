<?php

use App\Structure;
use App\Contact;

class StructureControllerTest extends ApiCase
{
    public function getUrl()
    {
        return '/api/structures';
    }

    public function getBaseStructure()
    {
        return ( new Structure() )->getFillable();
    }

    public function testStructureDetails()
    {
        $struct = factory('App\Structure')->create();
        $result = $struct->getAttributes();
        $result['contact'] = $struct->contact->getAttributes();

        $this->actingAs($this->user)
            ->get($this->getUrl() . '/' . $struct->id)
            ->seeJson($result);

        $struct->forceDelete();
    }
}
