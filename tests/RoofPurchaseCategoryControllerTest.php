<?php

use App\Roof\PurchaseCategory;

class RoofPurchaseCategoryControllerTest extends ApiCase
{
    protected function getUrl()
    {
        return '/api/roof/purchase_categories';
    }

    protected function getBaseStructure()
    {
        return ( new PurchaseCategory() )->getFillable();
    }

    protected function getFactoryClass()
    {
        return false;
    }
}
