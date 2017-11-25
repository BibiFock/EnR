<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Roof;

class RoofController extends Controller
{
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

    public function getProbabilities()
    {
        return response()->json(Roof::PROBABILITIES);
    }

    public function addRoof(Request $request)
    {
        $validator = $this->getValidator($request);

        if ($validator->fails()) {
            return $this->reportBadRequest($validator);
        }

        $roof = new Roof($request->all());
        $roof->save();

        return response()->json($roof);
    }

    public function updateRoof(Request $request, $id)
    {
        $validator = $this->getValidator($request);

        if ($validator->fails()) {
            return $this->reportBadRequest($validator);
        }

        try {
            $roof = Roof::findOrFail($id);
            $roof->fill($request->all());
            $roof->save();
        } catch (ModelNotFoundException $e) {
            return response()->json(['roof_not_found'], 404);
        }

        return response()->json($roof);
    }

    protected function getValidator(Request $request)
    {
        return \Validator::make(
            $request->all(),
            [
                'name' => 'string|required',
                'probability' => ['required', Rule::in(Roof::PROBABILITIES)],
                'square_area' => 'numeric',
                'power_max' => 'numeric',
                'power_min' => 'numeric',

                'erp' => 'boolean',
                'building_size' => 'numeric',
                'perimeter_abf' => 'boolean',
                'remarks' => 'string',

                'inverter_location' => 'string',
                'inverter_distance' => 'numeric',
                'street' => 'string|max:250',

                'zip' => 'string|max:250',
                'city' => 'string|max:250',
                'latitude' => 'numeric',
                'longitude' => 'numeric',

                // relations
                'owner_id' => 'numeric',
                'structure_id' => 'numeric',
                'south_orientation_id' => 'numeric',

                'purchase_category_id' => 'numeric',
                'type_id' => 'numeric',
                'tilt_id' => 'numeric',
                'department_id' => 'numeric'
            ]
        );
    }
}
