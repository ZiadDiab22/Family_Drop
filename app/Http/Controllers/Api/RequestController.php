<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment_way;
use App\Models\Pull_request;
use App\Models\User;
use App\Services\OrderService;
use App\Services\ProductService;
use App\Services\requestsService;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    protected $requestsService;
    protected $productService;
    protected $orderService;

    public function __construct(requestsService $requestsService, OrderService $orderService, ProductService $productService)
    {
        $this->requestsService = $requestsService;
        $this->productService = $productService;
        $this->orderService = $orderService;
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

    public function deletePullRequest($id)
    {
        if (!(Pull_request::where('id', $id)->exists())) {
            return response([
                'status' => false,
                'message' => 'Wrong id , not found',
            ]);
        }

        $user = User::find(Auth::user()->id);
        $req = Pull_request::find($id);

        $user->badget += $req->total;
        $user->save();
        Pull_request::where('id', $id)->delete();

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
