<?php

namespace App\Http\Controllers;

use App\Roof\PurchaseCategory;

class RoofPurchaseCategoryController extends Controller
{

    public function index()
    {
        $pc = PurchaseCategory::all();

        return response()->json($pc);
    }

}
