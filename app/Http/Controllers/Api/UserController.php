<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Payment_way;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Services\OrderService;
use App\Services\ProductService;
use App\Services\requestsService;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $requestsService;
    protected $orderService;
    protected $productService;
    protected $userService;

    public function __construct(
        requestsService $requestsService,
        OrderService $orderService,
        ProductService $productService,
        UserService $userService
    ) {
        $this->requestsService = $requestsService;
        $this->orderService = $orderService;
        $this->productService = $productService;
        $this->userService = $userService;
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'email|required',
            'password' => 'required',
            'phone_no' => 'required',
            'type_id' => 'required',
            'country_id' => 'required',
        ]);

        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'status' => false,
                'message' => "email is taken"
            ], 200);
        }

        if (User::where('phone_no', $request->phone_no)->exists()) {
            return response()->json([
                'status' => false,
                'message' => "phone number is taken"
            ], 200);
        }

        if ($request->type_id == 1 || $request->type_id == 2) {
            return response()->json([
                'status' => false,
                'message' => "this api isnt for create admins or employees"
            ], 200);
        }

        $validatedData['password'] = bcrypt($request->password);

        $user = User::create($validatedData);

        $accessToken = $user->createToken('authToken')->accessToken;

        $user_data = User::where('id', $user->id)->first();

        return response()->json([
            'status' => true,
            'access_token' => $accessToken,
            'user_data' => $user_data
        ]);
    }

    public function addEmp(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'email|required',
            'password' => 'required',
            'phone_no' => 'required',
            'type_id' => 'required',
            'country_id' => 'required',
        ]);

        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'status' => false,
                'message' => "email is taken"
            ], 200);
        }

        if (User::where('phone_no', $request->phone_no)->exists()) {
            return response()->json([
                'status' => false,
                'message' => "phone number is taken"
            ], 200);
        }

        $validatedData['password'] = bcrypt($request->password);

        $user = User::create($validatedData);

        $accessToken = $user->createToken('authToken')->accessToken;

        $user_data = User::where('id', $user->id)->first();

        return response()->json([
            'status' => true,
            'access_token' => $accessToken,
            'user_data' => $user_data
        ]);
    }

    public function login(Request $request)
    {
        $loginData = $request->validate([
            'password' => 'required',
            'email' => 'required'
        ]);

        if (!Auth::guard('web')->attempt(['password' => $loginData['password'], 'email' => $loginData['email']])) {
            return response()->json(['status' => false, 'message' => 'Invalid User'], 404);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        $user_data = User::where('email', $request->email)->first();

        return response()->json([
            'status' => true,
            'access_token' => $accessToken,
            'user_data' => $user_data
        ]);
    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        return response()->json([
            'status' => true,
            'message' => "User logged out successfully"
        ]);
    }

    public function editUserData(Request $request)
    {
        $request->validate([
            'email' => 'email',
        ]);

        $user = User::find(Auth::user()->id);

        $input = $request->all();

        if ($request->has('country_id')) {
            if (!(Country::where('id', $request->country_id)->exists())) {
                return response()->json([
                    'status' => false,
                    'message' => "Wrong country ID"
                ], 200);
            }
        }

        if ($request->has('phone_no')) {
            if (User::where('phone_no', $request->phone_no)->where('id', '!=', auth()->user()->id)->exists()) {
                return response()->json([
                    'status' => false,
                    'message' => "phone number is taken"
                ], 200);
            }
        }

        if ($request->has('email')) {
            if (User::where('email', $request->email)->where('id', '!=', auth()->user()->id)->exists()) {
                return response()->json([
                    'status' => false,
                    'message' => "email is taken"
                ], 200);
            }
        }

        foreach ($input as $key => $value) {
            if (in_array($key, ['name', 'country_id', 'email', 'phone_no']) && !empty($value)) {
                $user->$key = $value;
            }
        }

        $user->save();

        $user_data = User::where('users.id', auth()->user()->id)
            ->leftjoin('countries as c', 'country_id', 'c.id')
            ->join('user_types as t', 'type_id', 't.id')
            ->get([
                'users.id',
                'users.name',
                'country_id',
                'c.name as country_name',
                'type_id',
                't.name as type_name',
                'email',
                'phone_no',
                'badget',
                'created_at',
                'updated_at'
            ]);

        return response()->json([
            'status' => true,
            'message' => 'edited Successfully',
            'user_data' => $user_data,
        ]);
    }

    public function blockUser($id)
    {
        if (!(User::where('id', $id)->exists())) {
            return response([
                'status' => false,
                'message' => 'Wrong id , not found',
            ]);
        }

        $user = User::find($id);
        if ($user->blocked == 0) $user->blocked = 1;
        else $user->blocked = 0;
        $user->save();

        $data = User::where('id', '!=', Auth::user()->id)->get();

        return response([
            'status' => true,
            'message' => 'done Successfully',
            'data' => $data,
        ]);
    }

    public function activatePaymentWay($id)
    {
        if (!(Payment_way::where('id', $id)->exists())) {
            return response([
                'status' => false,
                'message' => 'Wrong id , not found',
            ]);
        }

        $way = Payment_way::find($id);
        if ($way->available == 0) $way->available = 1;
        else $way->available = 0;
        $way->save();

        $data = payment_way::get();

        return response([
            'status' => true,
            'message' => 'edited Successfully',
            'data' => $data
        ]);
    }

    public function showUsers()
    {
        $data = User::where('id', '!=', Auth::user()->id)->get();

        return response([
            'status' => true,
            'message' => 'done Successfully',
            'data' => $data,
        ]);
    }

    public function showRequests()
    {

        $pull_requests = $this->requestsService->getPullRequests();
        $add_product_request = $this->requestsService->getAddProductRequests();
        $pull_product_request = $this->requestsService->getPullProductRequests();

        return response([
            'status' => true,
            'pull_requests' => $pull_requests,
            'add_product_request' => $add_product_request,
            'pull_product_request' => $pull_product_request,
        ]);
    }

    public function profile()
    {
        $pull_requests = $this->requestsService->getUnfinishedUserPullRequests(Auth::user()->id);
        $total_pull_requests = $this->requestsService->getTotalUnfinishedUserPullRequests(Auth::user()->id);
        $user_info = $this->userService->getUserInfo(Auth::user()->id);

        if (Auth::user()->type_id == 4) {
            $TotalOrders = $this->orderService->getTotalFinishedCancelledNewOrders(Auth::user()->id);
            return response([
                'status' => true,
                'user_info' => $user_info,
                'pull_requests' => $pull_requests,
                'total_pull_requests' => $total_pull_requests,
                'finished_orders' => $TotalOrders[0],
                'cancelled_orders' => $TotalOrders[1],
                'new_orders' => $TotalOrders[2],
            ]);
        } else {
            $pinned_products = $this->requestsService->getPinnedProducts(Auth::user()->id);
            $products = $this->productService->getMercherProducts(Auth::user()->id);
            $pulled_products = $this->requestsService->getPulledProducts(Auth::user()->id);

            return response([
                'status' => true,
                'user_info' => $user_info,
                'pull_requests' => $pull_requests,
                'total_pull_requests' => $total_pull_requests,
                'pinned_products' => $pinned_products,
                'pulled_products' => $pulled_products,
                'products' => $products,
            ]);
        }
    }

    public function showUserInfo($id)
    {
        if (!(User::where('id', $id)->exists())) {
            return response([
                'status' => false,
                'message' => 'Wrong id , not found',
            ]);
        }

        $pull_requests = $this->requestsService->getUnfinishedUserPullRequests($id);
        $total_pull_requests = $this->requestsService->getTotalUnfinishedUserPullRequests($id);
        $user_info = $this->userService->getUserInfo($id);

        if ($user_info[0]->type_id == 4) {
            $TotalOrders = $this->orderService->getTotalFinishedCancelledNewOrders($id);
            return response([
                'status' => true,
                'user_info' => $user_info,
                'pull_requests' => $pull_requests,
                'total_pull_requests' => $total_pull_requests,
                'finished_orders' => $TotalOrders[0],
                'cancelled_orders' => $TotalOrders[1],
                'new_orders' => $TotalOrders[2],
            ]);
        } else {
            $pinned_products = $this->requestsService->getPinnedProducts($id);
            $products = $this->productService->getMercherProducts($id);
            $pulled_products = $this->requestsService->getPulledProducts($id);

            return response([
                'status' => true,
                'user_info' => $user_info,
                'pull_requests' => $pull_requests,
                'total_pull_requests' => $total_pull_requests,
                'pinned_products' => $pinned_products,
                'pulled_products' => $pulled_products,
                'products' => $products,
            ]);
        }
    }
}