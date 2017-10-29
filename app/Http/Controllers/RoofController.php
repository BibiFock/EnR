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
        foreach ($roofs as &$roof) {
            $roof->structure;
            $roof->owner;
        }

        return response()->json($roofs);
    }

    public function getRoof($id)
    {
        $roof = Roof::find($id);
        $extraInfos = array(
            'owner', 'structure', 'southOrientation',
            'purchaseCategory', 'type', 'tilt', 'department'
        );
        foreach ($extraInfos as $info) {
            $roof->{$info};
        }

        return response()->json($roof);
    }
}
