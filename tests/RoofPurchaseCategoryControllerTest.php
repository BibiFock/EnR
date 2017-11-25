<?php

use App\Roof\PurchaseCategory;

class RoofPurchaseCategoryControllerTest extends ApiCase
{
    public function getUrl()
    {
        return '/api/roof/purchase_categories';
    }

    public function getBaseStructure()
    {
        return ( new PurchaseCategory() )->getFillable();
    }
}
