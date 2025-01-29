<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\color;
use App\Models\Product;
use App\Models\product_color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function addColor(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        if ($request->has('code')) {
            $validatedData['code'] = $request->code;
        }

        color::create($validatedData);
        $data = color::get();

        return response([
            'status' => true,
            'message' => 'Added Successfully',
            'data' => $data
        ]);
    }

    public function showColors()
    {
        $data = color::get();

        return response([
            'status' => true,
            'data' => $data
        ]);
    }

    public function editColor(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        if (!(color::where('id', $request->id)->exists())) {
            return response([
                'status' => false,
                'message' => 'Wrong id , not found',
            ]);
        }

        $color = color::find($request->id);

        if ($request->has('name')) $color->name = $request->name;
        if ($request->has('code')) $color->code = $request->code;

        $color->save();

        $data = color::get();

        return response([
            'status' => true,
            'message' => 'edited Successfully',
            'data' => $data
        ]);
    }

    public function addProductColor(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'color_id' => 'required',
        ]);

        if (!(Product::where('id', $request->product_id)->exists())) {
            return response([
                'status' => false,
                'message' => 'Wrong product_id , not found',
            ]);
        }

        if (!(color::where('id', $request->color_id)->exists())) {
            return response([
                'status' => false,
                'message' => 'Wrong color_id , not found',
            ]);
        }
        
        if (product_color::where('product_id', $request->product_id)->where('color_id',$request->color_id)->exists()) {
            return response([
                'status' => false,
                'message' => 'already exists.',
            ]);
        }

        product_color::create([
            "product_id" => $request->product_id,
            "color_id" => $request->color_id,
        ]);

        $data = product_color::where('product_id', $request->product_id)
            ->join('colors as c', 'c.id', 'color_id')
            ->get(['product_id', 'color_id', 'name', 'code']);

        return response([
            'status' => true,
            'message' => 'Added Successfully',
            'data' => $data
        ]);
    }

    public function showProductColors($id)
    {
        if (!(Product::where('id', $id)->exists())) {
            return response([
                'status' => false,
                'message' => 'Wrong product_id , not found',
            ]);
        }

        $data = product_color::where('product_id', $id)
            ->join('colors as c', 'c.id', 'color_id')
            ->get(['product_id', 'color_id', 'name', 'code']);

        return response([
            'status' => true,
            'data' => $data
        ]);
    }
}
