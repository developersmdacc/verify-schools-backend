<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\Province;
use App\Models\City;

class LocationController extends Controller
{
    /**
     * Return all countries
     */
    public function getAllCountries()
    {
        $countries = Country::all(); // you can also select specific columns if needed
        return response()->json([
            'success' => true,
            'countries' => $countries
        ]);
    }

    /**
     * Return provinces for a given country
     */
    public function getCountryProvinces(string $id)
    {
        $country = Country::find($id);

        if (!$country) {
            return response()->json([
                'success' => false,
                'message' => 'Country not found'
            ], 404);
        }

        $provinces = $country->provinces()->get();

        return response()->json([
            'success' => true,
            'provinces' => $provinces
        ]);
    }

    /**
     * Return cities for a given province
     */
    public function getProvinceCities(string $id)
    {
        $province = Province::find($id);

        if (!$province) {
            return response()->json([
                'success' => false,
                'message' => 'Province not found'
            ], 404);
        }

        $cities = $province->cities()->get();

        return response()->json([
            'success' => true,
            'cities' => $cities
        ]);
    }
}
