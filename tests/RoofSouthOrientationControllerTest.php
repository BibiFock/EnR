<?php

use App\Roof\SouthOrientation;

class RoofSouthOrientationControllerTest extends ApiCase
{
    public function getUrl()
    {
        return '/api/roof/south_orientations';
    }

    public function getBaseStructure()
    {
        return ( new SouthOrientation() )->getFillable();
    }
}
