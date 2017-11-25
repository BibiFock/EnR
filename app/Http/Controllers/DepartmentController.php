<?php

namespace App\Http\Controllers;

use App\Department;

class DepartmentController extends Controller
{
    public function index()
    {
        $rows = Department::all();

        return response()->json($rows);
    }

}
