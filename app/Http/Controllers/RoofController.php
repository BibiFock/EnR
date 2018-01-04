<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Roof;
use App\Contact;

class RoofController extends Controller
{
    public function __construct()
    {
        $this->rules = [
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

            'slope' => 'numeric',
            'ground_square_area' => 'numeric',
            'occupancy_rate' => 'numeric',

            // relations
            'owner_id' => 'numeric',
            'structure_id' => 'numeric',
            'south_orientation_id' => 'numeric',

            'purchase_category_id' => 'numeric',
            'type_id' => 'numeric',
            'department_id' => 'numeric',

            // other
            'owner.contact.first_name' => 'string|max:200',
            'owner.contact.last_name' => 'string|max:200',
            'owner.type_id' => 'numeric',
            'owner.contact.phone' => 'string|max:50|nullable',
            'owner.contact.email' => 'string|max:250|nullable',
        ];
    }

    public function index()
    {
        $roofs = Roof::with(['structure', 'owner'])->get();

        return response()->json($roofs);
    }

    public function getRoof($id)
    {
        $roof = Roof::find($id);
        if (empty($roof)) {
            return response()->json(['roof_not_found'], 404);
        }

        return $this->renderRoof($roof);
    }

    public function getProbabilities()
    {
        return response()->json(Roof::PROBABILITIES);
    }

    public function addRoof(Request $request)
    {
        $this->validateRequest($request);

        $params = $this->validator->attributes();
        $roof = new Roof($params);
        $this->saveRoof($roof, $request->user());

        return $this->renderRoof($roof);
    }

    public function updateRoof(Request $request, $id)
    {
        $this->validateRequest($request);

        $params = $this->validator->attributes();
        try {
            $roof = Roof::findOrFail($id);
            $roof->fill($params);
            $this->saveRoof($roof, $request->user());
        } catch (ModelNotFoundException $e) {
            return response()->json(['roof_not_found'], 404);
        }

        return $this->renderRoof($roof);
    }

    protected function saveRoof($roof, $user)
    {
        $roof->save();
        $roof->saveState($user->id);
    }

    protected function renderRoof($roof)
    {
        return response()->json($roof->getCurrentState());
    }

    protected function validateRequest(Request $request)
    {
        parent::validateRequest($request);

        $roof = $this->validator->attributes();
        if (empty($roof['owner']['contact'])) {
            return true;
        }

        if (empty($roof['owner']['type_id'])) {
            $this->validator->errors()->add('owner.type_id', 'missing owner.type_id');

            $this->reportBadRequest();
        }

        $contact = $roof['owner']['contact'];
        if (empty($contact['first_name']) && empty($contact['last_name'])) {
            $this->validator->errors()->add('owner.*_name', 'missing first or last name');

            $this->reportBadRequest();
        }

        return true;
    }
}
