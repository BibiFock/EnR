<?php

namespace App\Http\Controllers;

use App\Roof\SouthOrientation;

class RoofSouthOrientationController extends Controller
{
    public function index()
    {
        $rows = SouthOrientation::all();

        return response()->json($rows);
    }

}
