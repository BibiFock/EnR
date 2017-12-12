<?php

use App\Structure;
use App\Contact;

class StructureControllerTest extends ApiCase
{
    public function testStructureDetails()
    {
        $struct = factory($this->getFactoryClass())->create();
        $result = $struct->getAttributes();
        $result['contact'] = $struct->contact->getAttributes();

        $this->actingAs($this->user)
            ->get($this->getUrl() . '/' . $struct->id)
            ->seeJson($result);

        $struct->forceDelete();
    }

    protected function getUrl()
    {
        return '/api/structures';
    }

    protected function getBaseStructure()
    {
        return ( new Structure() )->getFillable();
    }

    protected function getFactoryClass()
    {
        return Structure::class;
    }
}
