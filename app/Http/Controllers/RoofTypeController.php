<?php

namespace App\Http\Controllers;

use App\Roof\Type;

class RoofTypeController extends Controller
{
    public function index()
    {
        $rows = Type::all();

        return response()->json($rows);
    }

}
