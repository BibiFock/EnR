<?php

namespace App\Http\Controllers;

use App\Roof;
use App\Roof\Historical;

class RoofHistoricalController extends Controller
{
    public function index($id)
    {
        try {
            $roof = Roof::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['roof_not_found'], 404);
        }

        $states = $roof->states->all();
        $states = array_map(
            function ($row) {
                $row->state = json_decode($row['state'], true);
                $row->user;
                return $row;
            },
            $states
        );

        return response()->json($states);
    }

}
