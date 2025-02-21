<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\address;
use App\Models\City;
use App\Models\Country;
use App\Services\AddresseService;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    protected $addresseService;

    public function __construct(AddresseService $addresseService)
    {
        $this->addresseService = $addresseService;
    }

    public function addAddresse(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'city_id' => 'required',
        ]);

        if (!(City::where('id', $request->city_id)->exists())) {
            return response([
                'status' => false,
                'message' => 'Wrong city_id , not found',
            ]);
        }

        if ($request->has('delivery_price')) {
            if ($request->delivery_price >= 0)
                $validatedData['delivery_price'] = $request->delivery_price;
            else return response([
                'status' => false,
                'message' => 'delivery_price should be a positive value.',
            ]);
        }

        address::create($validatedData);

        $data = $this->addresseService->showAddresses();

        return response([
            'status' => true,
            'message' => 'Added Successfully',
            'data' => $data
        ]);
    }

    public function deleteAddresse($id)
    {
        if (!(address::where('id', $id)->exists())) {
            return response([
                'status' => false,
                'message' => 'Wrong id , not found',
            ]);
        }

        address::where('id', $id)->delete();

        $data = $this->addresseService->showAddresses();

        return response([
            'status' => true,
            'message' => 'deleted successfully',
            'data' => $data,
        ], 200);
    }

    public function editAddresse(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        if (!(address::where('id', $request->id)->exists())) {
            return response([
                'status' => false,
                'message' => 'Wrong id , not found',
            ]);
        }

        $ad = address::find($request->id);

        if ($request->has('city_id')) {
            if (!(City::where('id', $request->city_id)->exists())) {
                return response([
                    'status' => false,
                    'message' => 'Wrong id , city not found',
                ]);
            }
            $ad->city_id = $request->city_id;
        }

        if ($request->has('name')) $ad->name = $request->name;
        if ($request->has('delivery_price')) {
            if ($request->delivery_price >= 0)
                $ad->delivery_price = $request->delivery_price;
            else return response([
                'status' => false,
                'message' => 'delivery_price should be a positive value.',
            ]);
        }
        $ad->save();

        $data = $this->addresseService->showAddresses();

        return response([
            'status' => true,
            'message' => 'edited Successfully',
            'data' => $data
        ]);
    }

    public function showAddresses()
    {
        $data = $this->addresseService->showAddresses();

        return response([
            'status' => true,
            'data' => $data
        ]);
    }

    public function showLocations()
    {
        $addresses = $this->addresseService->showAddresses();

        $cities = City::join('countries as c', 'c.id', 'cities.country_id')
            ->get(['cities.id', 'cities.name', 'c.id as country_id', 'c.name as country_name']);

        $countries = Country::get();

        return response([
            'status' => true,
            'countries' => $countries,
            'cities' => $cities,
            'addresses' => $addresses
        ]);
    }

    public function blockAddresse($id)
    {
        if (!(address::where('id', $id)->exists())) {
            return response([
                'status' => false,
                'message' => 'Wrong id , not found',
            ]);
        }

        $ad = address::find($id);
        if ($ad->blocked == 0) $ad->blocked = 1;
        else $ad->blocked = 0;
        $ad->save();

        $data = $this->addresseService->showAddresses();

        return response([
            'status' => true,
            'data' => $data
        ]);
    }
}
