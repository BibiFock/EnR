<?php

use App\Roof\SouthOrientation;

class RoofSouthOrientationControllerTest extends ApiCase
{
    protected function getUrl()
    {
        return '/api/roof/south_orientations';
    }

    protected function getBaseStructure()
    {
        return ( new SouthOrientation() )->getFillable();
    }

    protected function getFactoryClass()
    {
        return false;
    }
}
