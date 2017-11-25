<?php

namespace App\Http\Controllers;

use App\Roof\Tilt;

class RoofTiltController extends Controller
{
    public function index()
    {
        $rows = Tilt::all();

        return response()->json($rows);
    }

}
