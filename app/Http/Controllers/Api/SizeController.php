<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\product_size;
use App\Models\size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    public function addSize(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        size::create($validatedData);
        $data = size::get();

        return response([
            'status' => true,
            'message' => 'Added Successfully',
            'data' => $data
        ]);
    }

    public function showSizes(Request $request)
    {
        $data = size::get();

        return response([
            'status' => true,
            'data' => $data
        ]);
    }

    public function editSize(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        if (!(size::where('id', $request->id)->exists())) {
            return response([
                'status' => false,
                'message' => 'Wrong id , not found',
            ]);
        }

        $size = size::find($request->id);

        if ($request->has('name')) $size->name = $request->name;

        $size->save();

        $data = size::get();

        return response([
            'status' => true,
            'message' => 'edited Successfully',
            'data' => $data
        ]);
    }

    public function addProductSize(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'size_id' => 'required',
        ]);

        if (!(Product::where('id', $request->product_id)->exists())) {
            return response([
                'status' => false,
                'message' => 'Wrong product_id , not found',
            ]);
        }

        if (!(size::where('id', $request->size_id)->exists())) {
            return response([
                'status' => false,
                'message' => 'Wrong size_id , not found',
            ]);
        }

        if (product_size::where('product_id', $request->product_id)->where('size_id',$request->size_id)->exists()) {
            return response([
                'status' => false,
                'message' => 'already exists.',
            ]);
        }

        product_size::create([
            "product_id" => $request->product_id,
            "size_id" => $request->size_id,
        ]);

        $data = product_size::where('product_id', $request->product_id)
            ->join('sizes as s', 's.id', 'size_id')
            ->get(['product_id', 'size_id', 'name']);

        return response([
            'status' => true,
            'message' => 'Added Successfully',
            'data' => $data
        ]);
    }

    public function showProductSizes($id)
    {

        if (!(Product::where('id', $id)->exists())) {
            return response([
                'status' => false,
                'message' => 'Wrong product_id , not found',
            ]);
        }

        $data = product_size::where('product_id', $id)
            ->join('sizes as s', 's.id', 'size_id')
            ->get(['product_id', 'size_id', 'name']);

        return response([
            'status' => true,
            'data' => $data
        ]);
    }
}
