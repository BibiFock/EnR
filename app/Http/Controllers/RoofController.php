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
        $roofs = Roof::with(['structure', 'owner'])->get();

        return response()->json($roofs);
    }

    public function getRoof($id)
    {
        $roof = Roof::with([
            'owner', 'structure', 'southOrientation',
            'purchaseCategory', 'type', 'tilt', 'department'
        ])->find($id);

        return response()->json($roof);
    }
}
