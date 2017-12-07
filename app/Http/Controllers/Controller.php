<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected $validator = null;
    protected $rules = null;

    protected function reportBadRequest($validator = null)
    {
        if (empty($validator)) {
            $validator = $this->validator;
        }

        response()->json($validator->errors(), 400)->throwResponse();
    }

    protected function validateRequest(Request $request)
    {
        $this->validator = \Validator::make(
            $request->only(array_keys($this->rules)),
            $this->rules
        );

        if ($this->validator->fails()) {
            $this->reportBadRequest($this->validator);
        }
    }
}
