<?php

use App\StructureType;

class StructureTypeControllerTest extends ApiCase
{
    protected function getUrl()
    {
        return '/api/structure_types';
    }

    protected function getBaseStructure()
    {
        return ( new StructureType() )->getFillable();
    }

    protected function getFactoryClass()
    {
        return false;
    }
}
