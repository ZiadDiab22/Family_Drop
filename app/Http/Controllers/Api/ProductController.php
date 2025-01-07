<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product_type;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function addProductType(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        Product_type::create($validatedData);
        $data = Product_type::get();

        return response([
            'status' => true,
            'message' => 'Added Successfully',
            'data' => $data
        ]);
    }

    public function deleteProductType($id)
    {

        if (!(Product_type::where('id', $id)->exists())) {
            return response([
                'status' => false,
                'message' => 'Wrong id , not found',
            ]);
        }

        Product_type::where('id', $id)->delete();

        $data = Product_type::get();

        return response([
            'status' => true,
            'message' => 'deleted successfully',
            'data' => $data,
        ], 200);
    }

    public function editProductType(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        if (!(Product_type::where('id', $request->id)->exists())) {
            return response([
                'status' => false,
                'message' => 'Wrong id , not found',
            ]);
        }

        $type = Product_type::find($request->id);

        if ($request->has('name')) $type->name = $request->name;

        $type->save();

        $data = Product_type::get();

        return response([
            'status' => true,
            'message' => 'edited Successfully',
            'data' => $data
        ]);
    }

    public function showProductTypes()
    {
        $data = Product_type::get();
        return response([
            'status' => true,
            'data' => $data
        ]);
    }
}
