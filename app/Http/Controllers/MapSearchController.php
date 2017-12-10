<?php

namespace App\Http\Controllers;

class MapSearchController extends Controller
{
    public function index()
    {
        return response()->make(
            file_get_contents(
                'http://nominatim.openstreetmap.org/search?' . $_SERVER['QUERY_STRING']
            )
        );
    }

}
