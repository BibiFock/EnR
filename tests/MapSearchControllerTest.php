<?php

class MapSearchControllerTest extends TestCase
{
    public function testList()
    {
        $response = $this->call('GET', $this->getUrl());
        $this->assertEquals(200, $response->status());

        $this->get($this->getUrl())
            ->seeJsonStructure([
                $this->getBaseStructure()
            ]);
    }


    protected function getUrl()
    {
        return '/api/map/search?q=maurecourt&format=json';
    }

    protected function getBaseStructure()
    {
        return [
            'display_name',
            'lat',
            'lon'
        ];
    }
}
