<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected function reportBadRequest($validator)
    {
        return response()->json($validator->errors(), 400);
    }
}
