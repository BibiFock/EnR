<?php

use App\Roof\Type;

class RoofTypeControllerTest extends ApiCase
{
    public function getUrl()
    {
        return '/api/roof/types';
    }

    public function getBaseStructure()
    {
        return ( new Type() )->getFillable();
    }
}
