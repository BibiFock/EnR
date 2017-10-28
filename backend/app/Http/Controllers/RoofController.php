<?php

namespace App\Http\Controllers;

use App\Roof;

class RoofController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }


    public function index()
    {
        $roofs = Roof::all();

        return response()->json($roofs);
    }
}
