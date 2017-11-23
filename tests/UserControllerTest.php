<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class UserControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->user = factory('App\User')->create();
    }

    public function tearDOWN()
    {
        $this->user->forceDelete();
        parent::tearDOWN();
    }

    public function testUsersList()
    {
        $this->get('/api/users')
            ->seeJsonStructure([[ 'id', 'name' ]]);
    }
}
