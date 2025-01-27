<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
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
}
