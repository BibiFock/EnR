<?php

use App\Department;

class DepartmentControllerTest extends ApiCase
{
    protected function getUrl()
    {
        return '/api/departments';
    }

    protected function getBaseStructure()
    {
        return ( new Department() )->getFillable();
    }

    protected function getFactoryClass()
    {
        return false;
    }
}
