<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product_classify;
use App\Models\Product_type;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function addProductClassify(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        Product_classify::create($validatedData);
        $data = Product_classify::get();

        return response([
            'status' => true,
            'message' => 'Added Successfully',
            'data' => $data
        ]);
    }

    public function deleteProductClassify($id)
    {

        if (!(Product_classify::where('id', $id)->exists())) {
            return response([
                'status' => false,
                'message' => 'Wrong id , not found',
            ]);
        }

        Product_classify::where('id', $id)->delete();

        $data = Product_classify::get();

        return response([
            'status' => true,
            'message' => 'deleted successfully',
            'data' => $data,
        ], 200);
    }

    public function editProductClassify(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required',
            'name' => 'required',
        ]);

        if (!(Product_classify::where('id', $request->id)->exists())) {
            return response([
                'status' => false,
                'message' => 'Wrong id , not found',
            ]);
        }

        $record = Product_classify::find($request->id);
        $record->fill($validatedData);
        $record->save();

        $data = Product_classify::get();

        return response([
            'status' => true,
            'message' => 'edited Successfully',
            'data' => $data
        ]);
    }

    public function showProductClassifies()
    {
        $data = Product_classify::get();

        return response([
            'status' => true,
            'data' => $data
        ]);
    }

    public function addProductType(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'classify_id' => 'required',
        ]);

        if (!(Product_classify::where('id', $request->classify_id)->exists())) {
            return response([
                'status' => false,
                'message' => 'Wrong id , not found',
            ]);
        }

        Product_type::create($validatedData);
        $data = Product_type::join('product_classifies as c', 'c.id', 'product_types.classify_id')
            ->get(['product_types.id', 'product_types.name', 'c.id as classify_id', 'c.name as classify_name']);

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

        $data = Product_type::join('product_classifies as c', 'c.id', 'product_types.classify_id')
            ->get(['product_types.id', 'product_types.name', 'c.id as classify_id', 'c.name as classify_name']);

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

        if ($request->has('classify_id')) {
            if (!(Product_classify::where('id', $request->classify_id)->exists())) {
                return response([
                    'status' => false,
                    'message' => 'Wrong id , classify not found',
                ]);
            }
            $type->classify_id = $request->classify_id;
        }

        if ($request->has('name')) $type->name = $request->name;

        $type->save();

        $data = Product_type::join('product_classifies as c', 'c.id', 'product_types.classify_id')
            ->get(['product_types.id', 'product_types.name', 'c.id as classify_id', 'c.name as classify_name']);

        return response([
            'status' => true,
            'message' => 'edited Successfully',
            'data' => $data
        ]);
    }

    public function showProductTypes()
    {
        $data = Product_type::join('product_classifies as c', 'c.id', 'product_types.classify_id')
            ->get(['product_types.id', 'product_types.name', 'c.id as classify_id', 'c.name as classify_name']);

        return response([
            'status' => true,
            'data' => $data
        ]);
    }
}
