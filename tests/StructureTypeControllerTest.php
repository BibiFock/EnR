<?php

use App\StructureType;

class StructureTypeControllerTest extends ApiCase
{
    public function getUrl()
    {
        return '/api/structure_types';
    }

    public function getBaseStructure()
    {
        return ( new StructureType() )->getFillable();
    }
}
