<?php

namespace App\Http\Controllers;

use App\StructureType;

class StructureTypeController extends Controller
{
    public function index()
    {
        $rows = StructureType::all();

        return response()->json($rows);
    }

}
