<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\product_color;
use App\Models\product_size;
use App\Models\Product_type;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
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

    public function addProduct(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'type_id' => 'required',
            'disc' => 'required',
            'cost_price' => 'required',
            'quantity' => 'required',
            'sales' => 'required',
            'selling_price' => 'required',
            'profit_rate' => 'required',
        ]);

        if ($request->quantity < 0) {
            return response()->json([
                'status' => false,
                'message' => "quantitiy couldnt be negative value"
            ], 200);
        }
        if (($request->cost_price < 0) || ($request->selling_price < 0)) {
            return response()->json([
                'status' => false,
                'message' => "price couldnt be negative value"
            ], 200);
        }

        $validatedData['owner_id'] = Auth::user()->id;

        if ($request->has('long_disc')) {
            $validatedData['long_disc'] = $request->long_disc;
        }

        $array = [];

        if ($request->has('images_array')) {
            foreach ($request->images_array as $img) {
                $image1 = Str::random(32) . "." . $img->getClientOriginalExtension();
                Storage::disk('public_htmlProducts')->put($image1, file_get_contents($img));
                $image1 = asset('products/' . $image1);
                $array[] = $image1;
            }
        }

        $validatedData['images_array'] = $array;

        $new_product = Product::create($validatedData);

        if ($request->has('colors')) {
            $colors = json_decode($request->colors, true);
            foreach ($colors as $color_id) {
                product_color::create([
                    "product_id" => $new_product->id,
                    "color_id" => $color_id,
                ]);
            }
        }

        if ($request->has('sizes')) {
            $sizes = json_decode($request->sizes, true);
            foreach ($sizes as $size_id) {
                product_size::create([
                    "product_id" => $new_product->id,
                    "size_id" => $size_id,
                ]);
            }
        }

        $data = product::join('product_types as pt', 'pt.id', 'products.type_id')
            ->join('users as u', 'u.id', 'owner_id')
            ->get([
                'products.id',
                'products.name',
                'disc',
                'long_disc',
                'products.type_id',
                'pt.name as type_name',
                'owner_id',
                'u.name as owner_name',
                'images_array',
                'cost_price',
                'selling_price',
                'quantity',
                'sales',
                'profit_rate',
                'products.blocked',
                'products.created_at',
                'products.updated_at'
            ]);

        foreach ($data as $p) {
            $sizes = product_size::where('product_id', $p['id'])
                ->join('sizes as s', 'size_id', 's.id')
                ->get(['size_id', 's.name as size_name']);

            $colors = product_color::where('product_id', $p['id'])
                ->join('colors as c', 'color_id', 'c.id')
                ->get(['color_id', 'c.name as color_name', 'code']);

            $p['sizes'] = $sizes;
            $p['colors'] = $colors;
        }

        $types = Product_type::get();

        return response()->json([
            'status' => true,
            'message' => 'product added Successfully',
            'products_types' => $types,
            'products' => $data,
        ]);
    }

    public function showProducts()
    {
        $data = product::join('product_types as pt', 'pt.id', 'products.type_id')
            ->join('users as u', 'u.id', 'owner_id')
            ->get([
                'products.id',
                'products.name',
                'disc',
                'long_disc',
                'products.type_id',
                'pt.name as type_name',
                'owner_id',
                'u.name as owner_name',
                'images_array',
                'cost_price',
                'selling_price',
                'quantity',
                'sales',
                'profit_rate',
                'products.blocked',
                'products.created_at',
                'products.updated_at'
            ]);

        foreach ($data as $p) {
            $sizes = product_size::where('product_id', $p['id'])
                ->join('sizes as s', 'size_id', 's.id')
                ->get(['size_id', 's.name as size_name']);

            $colors = product_color::where('product_id', $p['id'])
                ->join('colors as c', 'color_id', 'c.id')
                ->get(['color_id', 'c.name as color_name', 'code']);

            $p['sizes'] = $sizes;
            $p['colors'] = $colors;
        }

        $types = Product_type::get();

        return response()->json([
            'status' => true,
            'message' => 'product added Successfully',
            'products_types' => $types,
            'products' => $data,
        ]);
    }
}
