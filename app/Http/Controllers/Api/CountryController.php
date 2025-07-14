<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function addCountry(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        Country::create($validatedData);
        $data = Country::get();

        return response([
            'status' => true,
            'message' => 'Added Successfully',
            'data' => $data
        ]);
    }

    public function deleteCountry($id)
    {
        if (!(Country::where('id', $id)->exists())) {
            return response([
                'status' => false,
                'message' => 'Wrong id , not found',
            ]);
        }

        Country::where('id', $id)->delete();

        $data = Country::get();

        return response([
            'status' => true,
            'message' => 'deleted successfully',
            'data' => $data,
        ], 200);
    }

    public function editCountry(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required',
            'name' => 'required',
        ]);

        if (!(Country::where('id', $request->id)->exists())) {
            return response([
                'status' => false,
                'message' => 'Wrong id , not found',
            ]);
        }

        $country = Country::find($request->id);
        $country->fill($validatedData);
        $country->save();

        $data = Country::get();

        return response([
            'status' => true,
            'message' => 'edited Successfully',
            'data' => $data
        ]);
    }

    public function showCountries(Request $request)
    {
        $data = Country::get();

        return response([
            'status' => true,
            'data' => $data
        ]);
    }
}