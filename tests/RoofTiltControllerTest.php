<?php

use App\Roof\Tilt;

class RoofTiltControllerTest extends ApiCase
{
    public function getUrl()
    {
        return '/api/roof/tilts';
    }

    public function getBaseStructure()
    {
        return ( new Tilt() )->getFillable();
    }
}
