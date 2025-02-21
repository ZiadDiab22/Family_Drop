<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\addProductTypeRequest;
use App\Models\color;
use App\Models\Product;
use App\Models\product_color;
use App\Models\product_size;
use App\Models\Product_type;
use App\Models\size;
use App\Models\User;
use App\Services\AddresseService;
use App\Services\ProductService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;
    protected $addresseService;

    public function __construct(ProductService $productService, AddresseService $addresseService)
    {
        $this->productService = $productService;
        $this->addresseService = $addresseService;
    }

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

    public function editProductType(addProductTypeRequest $request)
    {
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

        $data = $this->productService->showProducts();
        $types = $this->productService->showProductTypes();

        return response()->json([
            'status' => true,
            'message' => 'product added Successfully',
            'products_types' => $types,
            'products' => $data,
        ]);
    }

    public function editProduct(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        if (!(Product::where('id', $request->id)->exists())) {
            return response([
                'status' => false,
                'message' => 'Wrong id , not found',
            ]);
        }

        $product = product::find($request->id);

        if ($request->has('name')) $product->name = $request->name;
        if ($request->has('disc')) $product->disc = $request->disc;
        if ($request->has('long_disc')) $product->long_disc = $request->long_disc;
        if ($request->has('sales')) $product->sales = $request->sales;
        if ($request->has('profit_rate')) $product->profit_rate = $request->profit_rate;
        if ($request->has('type_id')) {
            if (!(Product_type::where('id', $request->type_id)->exists())) {
                return response([
                    'status' => false,
                    'message' => 'Wrong type_id , not found',
                ]);
            }
            $product->type_id = $request->type_id;
        }
        if ($request->has('owner_id')) {
            if (!(User::where('id', $request->owner_id)->exists())) {
                return response([
                    'status' => false,
                    'message' => 'Wrong owner_id , not found',
                ]);
            }
            $product->owner_id = $request->owner_id;
        }
        if ($request->has('cost_price')) {
            if ($request->cost_price < 0)
                return response()->json([
                    'status' => false,
                    'message' => "cost_price couldnt be negative value"
                ], 200);
            $product->cost_price = $request->cost_price;
        }
        if ($request->has('selling_price')) {
            if ($request->selling_price < 0)
                return response()->json([
                    'status' => false,
                    'message' => "selling_price couldnt be negative value"
                ], 200);
            $product->selling_price = $request->selling_price;
        }
        if ($request->has('quantity')) {
            if ($request->quantity < 0)
                return response()->json([
                    'status' => false,
                    'message' => "quantitiy couldnt be negative value"
                ], 200);
            $product->quantity = $request->quantity;
        }

        $array = [];
        if ($request->has('images_array')) {
            foreach ($product->images_array as $name) {
                $parts = explode('products', $name);
                $filteredParts = array_filter($parts);
                $path = end($filteredParts);
                Storage::disk('public_htmlProducts')->delete($path);
            }
            foreach ($request->images_array as $img) {
                $image1 = Str::random(32) . "." . $img->getClientOriginalExtension();
                Storage::disk('public_htmlProducts')->put($image1, file_get_contents($img));
                $image1 = asset('products/' . $image1);
                $array[] = $image1;
            }
            $product->images_array = $array;
        }

        if ($request->has('colors')) {
            product_color::where('product_id', $request->id)->delete();
            $colors = json_decode($request->colors, true);
            foreach ($colors as $color_id) {
                product_color::create([
                    "product_id" => $request->id,
                    "color_id" => $color_id,
                ]);
            }
        }

        if ($request->has('sizes')) {
            product_size::where('product_id', $request->id)->delete();
            $sizes = json_decode($request->sizes, true);
            foreach ($sizes as $size_id) {
                product_size::create([
                    "product_id" => $request->id,
                    "size_id" => $size_id,
                ]);
            }
        }

        $product->save();

        $products = $this->productService->showProducts();
        $types = $this->productService->showProductTypes();

        return response()->json([
            'status' => true,
            'message' => 'product edited Successfully',
            'products_types' => $types,
            'products' => $products,
        ]);
    }

    public function showProducts()
    {
        $data = $this->productService->showProducts();
        $types = $this->productService->showProductTypes();

        return response()->json([
            'status' => true,
            'products_types' => $types,
            'products' => $data,
        ]);
    }

    public function deleteProduct($id)
    {
        if (!(Product::where('id', $id)->exists())) {
            return response([
                'status' => false,
                'message' => 'Wrong id , not found',
            ]);
        }

        $product = Product::find($id);
        $array = $product->images_array;

        if ($array) {
            foreach ($array as $name) {
                $parts = explode('products', $name);
                $filteredParts = array_filter($parts);
                $path = end($filteredParts);
                Storage::disk('public_htmlProducts')->delete($path);
            }
        }

        product::where('id', $id)->delete();

        $data = $this->productService->showProducts();
        $types = $this->productService->showProductTypes();

        return response()->json([
            'status' => true,
            'products_types' => $types,
            'products' => $data,
        ]);
    }

    public function showTypesSizesColors()
    {
        $types = Product_type::get();
        $sizes = size::get();
        $colors = color::get();

        return response([
            'status' => true,
            'types' => $types,
            'sizes' => $sizes,
            'colors' => $colors
        ]);
    }

    public function showProductInfo($id)
    {
        if (!(Product::where('id', $id)->exists())) {
            return response([
                'status' => false,
                'message' => 'Wrong id , not found',
            ]);
        }

        $product = $this->productService->showProductInfo($id);
        $types = $this->productService->showProductTypes();

        return response()->json([
            'status' => true,
            'products_types' => $types,
            'product' => $product,
        ]);
    }

    public function searchProducts(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        if ($request->name == 0) {
            $product = $this->productService->showAllProductsOrdered();
        } else
            $product = $this->productService->showProductsOrdered($request->name);

        $types = $this->productService->showProductTypes();

        return response()->json([
            'status' => true,
            'products' => $product,
            'types' => $types,
        ]);
    }

    public function blockProduct($id)
    {
        if (!(Product::where('id', $id)->exists())) {
            return response([
                'status' => false,
                'message' => 'Wrong id , not found',
            ]);
        }

        $product = Product::find($id);
        if ($product->blocked == 0) $product->blocked = 1;
        else $product->blocked = 0;
        $product->save();

        $products = $this->productService->showProducts();
        $types = $this->productService->showProductTypes();

        return response([
            'status' => true,
            'message' => 'done Successfully',
            'products' => $products,
            'types' => $types,
        ]);
    }
}
