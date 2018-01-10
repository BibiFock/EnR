<?php

namespace App\Http\Controllers\Roof;

use App\Http\Controllers\Controller;
use App\Roof\Tilt\Type;

class TiltTypeController extends Controller
{
    public function index()
    {
        $rows = Type::all();

        return response()->json($rows);
    }

}
