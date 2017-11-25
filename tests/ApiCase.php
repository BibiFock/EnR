<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

abstract class ApiCase extends TestCase
{
    use DatabaseTransactions;

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

    public function testNeedToken()
    {
        $response = $this->call('GET', $this->getUrl());

        $this->assertEquals(401, $response->status());
    }

    public function testList()
    {
        $this->actingAs($this->user)
            ->get($this->getUrl())
            ->seeJsonStructure([
                $this->getBaseStructure()
            ]);
    }

    abstract public function getUrl();
    abstract public function getBaseStructure();
}
