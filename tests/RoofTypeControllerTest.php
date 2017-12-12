<?php

use App\Roof\Type;

class RoofTypeControllerTest extends ApiCase
{
    protected function getUrl()
    {
        return '/api/roof/types';
    }

    protected function getBaseStructure()
    {
        return ( new Type() )->getFillable();
    }

    protected function getFactoryClass()
    {
        return false;
    }
}
