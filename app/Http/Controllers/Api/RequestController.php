<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Add_product_request;
use App\Models\address;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment_way;
use App\Models\Product;
use App\Models\pull_product_request;
use App\Models\Pull_request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Services\AddresseService;
use App\Services\OrderService;
use App\Services\ProductService;
use App\Services\requestsService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    protected $requestsService;
    protected $productService;
    protected $orderService;
    protected $addresseService;

    public function __construct(
        requestsService $requestsService,
        OrderService $orderService,
        ProductService $productService,
        AddresseService $addresseService
    ) {
        $this->requestsService = $requestsService;
        $this->productService = $productService;
        $this->orderService = $orderService;
        $this->addresseService = $addresseService;
    }

    public function pullMoneyRequest(Request $request)
    {
        $validatedData = $request->validate([
            'payment_way_id' => 'required',
            'total' => 'required',
        ]);

        if (!(Payment_way::where('id', $request->payment_way_id)->exists())) {
            return response([
                'status' => false,
                'message' => 'Wrong payment_way_id , not found',
            ]);
        }

        $user = User::find(Auth::user()->id);

        if ($request->total > $user->badget) {
            return response([
                'status' => false,
                'message' => 'you dont have enough badget',
            ]);
        }

        $validatedData['user_id'] = $user->id;
        Pull_request::create($validatedData);

        $user->badget -= $request->total;
        $user->save();

        $pull_requests = $this->requestsService->getUserPullRequests($user->id);
        if ($user->type_id == 4) {
            $orders = $this->orderService->getUserFinishedOrders($user->id);
            return response([
                'status' => true,
                'pull_requests' => $pull_requests,
                'orders' => $orders
            ]);
        } else {
            $products = $this->productService->getMercherProducts($user->id);
            return response([
                'status' => true,
                'pull_requests' => $pull_requests,
                'products' => $products
            ]);
        }
    }

    public function showPullRequests()
    {
        if (Auth::user()->type_id < 3) {
            $pull_requests = $this->requestsService->getPullRequests();
            return response([
                'status' => true,
                'pull_requests' => $pull_requests
            ]);
        } else {
            $pull_requests = $this->requestsService->getUserPullRequests(Auth::user()->id);
            if (Auth::user()->type_id == 4) {
                $orders = $this->orderService->getUserFinishedOrders(Auth::user()->id);
                return response([
                    'status' => true,
                    'pull_requests' => $pull_requests,
                    'orders' => $orders
                ]);
            } else {
                $products = $this->productService->getMercherProducts(Auth::user()->id);
                return response([
                    'status' => true,
                    'pull_requests' => $pull_requests,
                    'products' => $products
                ]);
            }
        }
    }

    public function deletePullRequest($id)
    {
        if (!(Pull_request::where('id', $id)->exists())) {
            return response([
                'status' => false,
                'message' => 'Wrong id , not found',
            ]);
        }

        if (!(Pull_request::where('id', $id)->where('user_id', Auth::user()->id)->exists())) {
            if (Auth::user()->type_id > 2)
                return response([
                    'status' => false,
                    'message' => 'You dont have access to this request.',
                ]);
        }

        $req = Pull_request::find($id);
        $user = User::find($req->user_id);

        $user->badget += $req->total;
        $user->save();
        Pull_request::where('id', $id)->delete();

        if (Auth::user()->type_id < 3) {
            $pull_requests = $this->requestsService->getPullRequests();
            return response([
                'status' => true,
                'pull_requests' => $pull_requests
            ]);
        } else {
            $pull_requests = $this->requestsService->getUserPullRequests(Auth::user()->id);
            if (Auth::user()->type_id == 4) {
                $orders = $this->orderService->getUserFinishedOrders(Auth::user()->id);
                return response([
                    'status' => true,
                    'pull_requests' => $pull_requests,
                    'orders' => $orders
                ]);
            } else {
                $products = $this->productService->getMercherProducts(Auth::user()->id);
                return response([
                    'status' => true,
                    'pull_requests' => $pull_requests,
                    'products' => $products
                ]);
            }
        }
    }

    public function addProductRequest(Request $request)
    {
        $validatedData = $request->validate([
            'addresse_id' => 'required',
            'product_name' => 'required',
            'images_array' => 'required',
            'product_quantity' => 'required',
            'product_price' => 'required',
            'product_disc' => 'required',
        ]);

        if ($request->product_quantity < 0) {
            return response()->json([
                'status' => false,
                'message' => "quantitiy couldnt be negative value"
            ], 200);
        }
        if ($request->product_price < 0) {
            return response()->json([
                'status' => false,
                'message' => "price couldnt be negative value"
            ], 200);
        }

        if (!(address::where('id', $request->addresse_id)->exists())) {
            return response([
                'status' => false,
                'message' => 'Wrong addresse_id , not found',
            ]);
        }

        $validatedData['product_place'] = $request->addresse_id;
        $validatedData['user_id'] = Auth::user()->id;

        $array = [];

        foreach ($request->images_array as $img) {
            $image1 = Str::random(32) . "." . $img->getClientOriginalExtension();
            Storage::disk('public_htmlProducts')->put($image1, file_get_contents($img));
            $image1 = asset('products/' . $image1);
            $array[] = $image1;
        }

        $validatedData['images_array'] = $array;

        Add_product_request::create($validatedData);

        $add_product_requests = $this->productService->showUserAddProductRequests(Auth::user()->id);
        $addresses = $this->addresseService->showAddresses();

        return response()->json([
            'status' => true,
            'message' => 'added Successfully',
            'add_product_requests' => $add_product_requests,
            'addresses' => $addresses,
        ]);
    }

    public function showProductRequests()
    {
        $addresses = $this->addresseService->showAddresses();

        if (Auth::user()->type_id < 3) {
            $add_product_requests = $this->requestsService->getAddProductRequests();
        } else $add_product_requests = $this->productService->showUserAddProductRequests(Auth::user()->id);

        return response()->json([
            'status' => true,
            'add_product_requests' => $add_product_requests,
            'addresses' => $addresses,
        ]);
    }

    public function deleteProductRequest($id)
    {
        if (!(Add_product_request::where('id', $id)->exists())) {
            return response([
                'status' => false,
                'message' => 'Wrong id , not found',
            ]);
        }

        if (!(Add_product_request::where('id', $id)->where('user_id', Auth::user()->id)->exists())) {
            if (Auth::user()->type_id == 3)
                return response([
                    'status' => false,
                    'message' => 'you dont have access to this product.',
                ]);
        }

        $product = Add_product_request::find($id);
        $array = $product->images_array;

        if ($array) {
            foreach ($array as $name) {
                $parts = explode('products', $name);
                $filteredParts = array_filter($parts);
                $path = end($filteredParts);
                Storage::disk('public_htmlProducts')->delete($path);
            }
        }

        Add_product_request::where('id', $id)->delete();

        $addresses = $this->addresseService->showAddresses();

        if (Auth::user()->type_id < 3) {
            $add_product_requests = $this->requestsService->getAddProductRequests();
        } else $add_product_requests = $this->productService->showUserAddProductRequests(Auth::user()->id);

        return response()->json([
            'status' => true,
            'add_product_requests' => $add_product_requests,
            'addresses' => $addresses,
        ]);
    }

    public function PullProductRequest(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required',
            'quantity' => 'required',
        ]);

        if (!(Product::where('id', $request->product_id)->exists())) {
            return response([
                'status' => false,
                'message' => 'Wrong product_id , not found',
            ]);
        }

        if (!(Product::where('id', $request->product_id)->where('owner_id', Auth::user()->id)->exists())) {
            return response([
                'status' => false,
                'message' => 'You dont have permission to pull from this product.',
            ]);
        }

        $product = Product::find($request->product_id);

        if ($request->quantity > $product->quantity) {
            return response([
                'status' => false,
                'message' => 'we dont have enough quantity',
            ]);
        }

        $product->quantity -= $request->quantity;
        $product->save();

        $validatedData['mercher_id'] = Auth::user()->id;
        pull_product_request::create($validatedData);

        $pull_requests = $this->requestsService->getUserPullProductRequests(Auth::user()->id);
        $products = $this->productService->getMercherProducts(Auth::user()->id);

        return response([
            'status' => true,
            'pull_requests' => $pull_requests,
            'products' => $products
        ]);
    }

    public function showPullProductRequests()
    {
        if (Auth::user()->type_id == 3) {
            $pull_requests = $this->requestsService->getUserPullProductRequests(Auth::user()->id);
            $products = $this->productService->getMercherProducts(Auth::user()->id);
        } else {
            $pull_requests = $this->requestsService->getPullProductRequests();
            $products = $this->productService->showProducts();
        }

        return response([
            'status' => true,
            'pull_requests' => $pull_requests,
            'products' => $products
        ]);
    }

    public function deletePullProductRequest($id)
    {
        if (!(pull_product_request::where('id', $id)->exists())) {
            return response([
                'status' => false,
                'message' => 'Wrong id , not found',
            ]);
        }

        if (!(pull_product_request::where('id', $id)->where('mercher_id', Auth::user()->id)->exists())) {
            if (Auth::user()->type_id == 3)
                return response([
                    'status' => false,
                    'message' => 'you dont have access to this product.',
                ]);
        }

        $req = pull_product_request::find($id);
        $product = Product::find($req->product_id);

        $product->quantity += $req->quantity;
        $product->save();
        pull_product_request::where('id', $id)->delete();

        if (Auth::user()->type_id == 3) {
            $pull_requests = $this->requestsService->getUserPullProductRequests(Auth::user()->id);
            $products = $this->productService->getMercherProducts(Auth::user()->id);
        } else {
            $pull_requests = $this->requestsService->getPullProductRequests();
            $products = $this->productService->showProducts();
        }

        return response([
            'status' => true,
            'pull_requests' => $pull_requests,
            'products' => $products
        ]);
    }

    public function acceptPullMoneyRequest($id)
    {
        if (!(Pull_request::where('id', $id)->exists())) {
            return response([
                'status' => false,
                'message' => 'Wrong id , not found',
            ]);
        }

        $req = Pull_request::find($id);

        if ($req->accepted == 0) {
            $req->accepted = 1;
            $req->employee_id = Auth::user()->id;
            $req->save();
        }

        $pull_requests = $this->requestsService->getPullRequests();

        return response([
            'status' => true,
            'pull_requests' => $pull_requests
        ]);
    }

    public function acceptPullProductRequest($id)
    {
        if (!(pull_product_request::where('id', $id)->exists())) {
            return response([
                'status' => false,
                'message' => 'Wrong id , not found',
            ]);
        }

        $req = pull_product_request::find($id);

        if ($req->accepted == 0) {
            $req->accepted = 1;
            $req->employee_id = Auth::user()->id;
            $req->save();
        }

        $pull_product_requests = $this->requestsService->getPullProductRequests();
        $products = $this->productService->showProducts();
        $addresses = $this->addresseService->showAddresses();

        return response()->json([
            'status' => true,
            'pull_product_requests' => $pull_product_requests,
            'products' => $products,
            'addresses' => $addresses,
        ]);
    }

    public function acceptAddProductRequest($id)
    {
        if (!(Add_product_request::where('id', $id)->exists())) {
            return response([
                'status' => false,
                'message' => 'Wrong id , not found',
            ]);
        }

        $req = Add_product_request::find($id);

        if ($req->accepted == 0) {
            $req->accepted = 1;
            $req->employee_id = Auth::user()->id;
            $req->save();

            $data = [];
            $data['name'] = $req->product_name;
            $data['images_array'] = $req->images_array;
            $data['quantity'] = $req->product_quantity;
            $data['cost_price'] = $req->product_price;
            $data['selling_price'] = $req->product_price * 1.1;
            $data['disc'] = $req->product_disc;
            $data['sales'] = 0;
            $data['owner_id'] =  $req->user_id;
            $data['type_id'] =  DB::table('product_types')->value('id');
            Product::create($data);
        }

        $add_product_requests = $this->requestsService->getAddProductRequests();
        $products = $this->productService->showProducts();
        $addresses = $this->addresseService->showAddresses();

        return response()->json([
            'status' => true,
            'add_product_requests' => $add_product_requests,
            'products' => $products,
            'addresses' => $addresses,
        ]);
    }
}
