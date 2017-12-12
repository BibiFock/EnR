<?php

use App\Roof\Tilt;

class RoofTiltControllerTest extends ApiCase
{
    protected function getUrl()
    {
        return '/api/roof/tilts';
    }

    protected function getBaseStructure()
    {
        return ( new Tilt() )->getFillable();
    }

    protected function  getFactoryClass()
    {
        return false;
    }
}
