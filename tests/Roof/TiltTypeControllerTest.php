<?php

use App\Roof\Tilt\Type;

class TiltTypeControllerTest extends ApiCase
{
    protected function getUrl()
    {
        return '/api/roof/tilt/types';
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
