<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Faker\Factory as Faker;

class MapSearchController extends Controller
{
    public function index(Request $request)
    {
        $url = 'https://nominatim.openstreetmap.org/search?'
            . $request->server('QUERY_STRING');

        $ch = curl_init($url);

        $faker = Faker::create();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'User-Agent: ' . $faker->userAgent
        ));

        $searchContent = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($httpCode === 200) {
            return response()->make(
                $searchContent
            );
        }

        return response()->make(array(
            'failed' => true,
            'message' => 'error ' . $httpCode
        ));
    }

}
