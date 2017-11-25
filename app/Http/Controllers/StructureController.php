<?php

namespace App\Http\Controllers;

use App\Structure;

class StructureController extends Controller
{

    public function index()
    {
        $structures = Structure::getAllInitStructure();

        return response()->json($structures);
    }

}
