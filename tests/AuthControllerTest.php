<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class AuthControllerTest extends TestCase
{
    protected $user = null;
    protected $password = '123';

    public function setUp()
    {
        parent::setUp();
        $this->user = factory('App\User')->create([
            'password' => app('hash')->make($this->password)
        ]);
    }

    public function tearDOWN()
    {
        $this->user->forceDelete();
        parent::tearDOWN();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testLogin()
    {
        $response = $this->call(
            'POST',
            '/api/auth/login',
            [
                'id' => $this->user->id,
                'password' => $this->password
            ]
        );

        $this->assertEquals(200, $response->status());
    }

    public function testBadLogin()
    {
        $response = $this->call(
            'POST',
            '/api/auth/login',
            [
                'id' => $this->user->id,
                'password' => $this->password . '23'
            ]
        );

        $this->assertEquals(404, $response->status());
    }
}
