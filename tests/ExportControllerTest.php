<?php

use App\Roof;

class ExportControllerTest extends ApiCase
{
    public function testList()
    {
        $response = $this->actingAs($this->user)
            ->call('GET', $this->getUrl());

        $this->assertTrue(
            $response->headers->contains(
                'Content-Disposition',
                'attachment; filename=export-photovolt.csv'
            )
        );

        $this->assertTrue(
            $response->headers->contains('Content-type', 'text/csv')
        );
    }

    protected function getUrl()
    {
        return '/api/export';
    }

    protected function getBaseStructure()
    {
        return ( new StructureType() )->getFillable();
    }

    protected function getFactoryClass()
    {
        return false;
    }
}
