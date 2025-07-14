<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function addCity(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'country_id' => 'required',
        ]);

        if (!(Country::where('id', $request->country_id)->exists())) {
            return response([
                'status' => false,
                'message' => 'Wrong id , not found',
            ]);
        }

        City::create($validatedData);
        $data = City::join('countries as c', 'c.id', 'cities.country_id')
            ->get(['cities.id', 'cities.name', 'c.id as country_id', 'c.name as country_name']);

        return response([
            'status' => true,
            'message' => 'Added Successfully',
            'data' => $data
        ]);
    }

    public function deleteCity($id)
    {

        if (!(City::where('id', $id)->exists())) {
            return response([
                'status' => false,
                'message' => 'Wrong id , not found',
            ]);
        }

        City::where('id', $id)->delete();

        $data = City::join('countries as c', 'c.id', 'cities.country_id')
            ->get(['cities.id', 'cities.name', 'c.id as country_id', 'c.name as country_name']);

        return response([
            'status' => true,
            'message' => 'deleted successfully',
            'data' => $data,
        ], 200);
    }

    public function editCity(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        if (!(City::where('id', $request->id)->exists())) {
            return response([
                'status' => false,
                'message' => 'Wrong id , not found',
            ]);
        }

        $city = City::find($request->id);

        if ($request->has('country_id')) {
            if (!(Country::where('id', $request->country_id)->exists())) {
                return response([
                    'status' => false,
                    'message' => 'Wrong id , country not found',
                ]);
            }
            $city->country_id = $request->country_id;
        }

        if ($request->has('name')) $city->name = $request->name;

        $city->save();

        $data = City::join('countries as c', 'c.id', 'cities.country_id')
            ->get(['cities.id', 'cities.name', 'c.id as country_id', 'c.name as country_name']);

        return response([
            'status' => true,
            'message' => 'edited Successfully',
            'data' => $data
        ]);
    }

    public function showCities()
    {
        $data = City::join('countries as c', 'c.id', 'cities.country_id')
            ->get(['cities.id', 'cities.name', 'c.id as country_id', 'c.name as country_name']);

        return response([
            'status' => true,
            'data' => $data
        ]);
    }
}
