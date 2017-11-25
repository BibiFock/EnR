<?php

use App\Department;

class DepartmentControllerTest extends ApiCase
{
    public function getUrl()
    {
        return '/api/departments';
    }

    public function getBaseStructure()
    {
        return ( new Department() )->getFillable();
    }
}
