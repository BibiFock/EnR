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
        $factory = $this->getFactoryClass();
        if (!empty($factory)) {
            $object = factory($factory)->create();
        }

        $response = $this->actingAs($this->user)
            ->call('GET', $this->getUrl());
        $this->assertEquals(200, $response->status());

        $this->actingAs($this->user)
            ->get($this->getUrl())
            ->seeJsonStructure([
                $this->getBaseStructure()
            ]);

        if (!empty($object)) {
            $object->forceDelete();
        }
    }

    abstract protected function getUrl();
    abstract protected function getBaseStructure();
    abstract protected function getFactoryClass();
}
