<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\ColorController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\LinkController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RequestController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\SizeController;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\adm_emp;
use App\Http\Middleware\AllowAdmin;
use App\Http\Middleware\AllowNonMarketers;
use App\Http\Middleware\AllowNonMerhers;
use App\Http\Middleware\mark;
use App\Http\Middleware\merch;
use App\Http\Middleware\mm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post("register", [UserController::class, "register"]);
Route::post("login", [UserController::class, "login"]);
Route::get("showCountries", [CountryController::class, "showCountries"]);
Route::get("showCities", [CityController::class, "showCities"]);
Route::get("showProductTypes", [ProductController::class, "showProductTypes"]);
Route::get("showAddresses", [AddressController::class, "showAddresses"]);
Route::get("showLocations", [AddressController::class, "showLocations"]);
Route::get("showProducts", [ProductController::class, "showProducts"]);
Route::get("showSizes", [SizeController::class, "showSizes"]);
Route::get("showColors", [ColorController::class, "showColors"]);
Route::get("showLinks", [LinkController::class, "showLinks"]);
Route::get("showPaymentWays", [PaymentController::class, "showPaymentWays"]);
Route::get("showProductSizes/{id}", [SizeController::class, "showProductSizes"]);
Route::get("showProductColors/{id}", [ColorController::class, "showProductColors"]);
Route::get("showTypesSizesColors", [ProductController::class, "showTypesSizesColors"]);
Route::get("showProductInfo/{id}", [ProductController::class, "showProductInfo"]);
Route::post("searchProducts", [ProductController::class, "searchProducts"]);
Route::get("showSettings", [SettingController::class, "showSettings"]);
Route::post("upload", [SettingController::class, "upload"]);
Route::post("installVideo", [ProductController::class, "installVideo"]);

Route::group(["middleware" => ["auth:api"]], function () {
    Route::post("addEmp", [UserController::class, "addEmp"])->middleware(adm_emp::class);
    Route::get("logout", [UserController::class, "logout"]);
    Route::post("editUserData", [UserController::class, "editUserData"]);
    Route::post("updateUserData", [UserController::class, "updateUserData"])->middleware(adm_emp::class);
    Route::post("addCountry", [CountryController::class, "addCountry"])->middleware(adm_emp::class);
    Route::get("deleteCountry/{id}", [CountryController::class, "deleteCountry"])->middleware(adm_emp::class);
    Route::post("editCountry", [CountryController::class, "editCountry"])->middleware(adm_emp::class);
    Route::post("addProductType", [ProductController::class, "addProductType"])->middleware(adm_emp::class);
    Route::get("deleteProductType/{id}", [ProductController::class, "deleteProductType"])->middleware(adm_emp::class);
    Route::post("editProductType", [ProductController::class, "editProductType"])->middleware(adm_emp::class);
    Route::post("addCity", [CityController::class, "addCity"])->middleware(adm_emp::class);
    Route::post("addLink", [LinkController::class, "addLink"])->middleware(adm_emp::class);
    Route::get("deleteLink/{id}", [LinkController::class, "deleteLink"])->middleware(adm_emp::class);
    Route::post("editLink", [LinkController::class, "editLink"])->middleware(adm_emp::class);
    Route::post("addPaymentWay", [PaymentController::class, "addPaymentWay"])->middleware(adm_emp::class);
    Route::post("editPaymentWay", [PaymentController::class, "editPaymentWay"])->middleware(adm_emp::class);
    Route::post("addCity", [CityController::class, "addCity"])->middleware(adm_emp::class);
    Route::get("deleteCity/{id}", [CityController::class, "deleteCity"])->middleware(adm_emp::class);
    Route::post("editCity", [CityController::class, "editCity"])->middleware(adm_emp::class);
 
    Route::post('verify', function (Request $request) {
        if (!$request->user()->hasVerifiedEmail()) {
            $request->user()->sendEmailVerificationNotification();

            return response()->json([
                'message' => 'Verification email sent successfully'
            ]);
        }

        return response()->json([
            'message' => 'Email is already verified'
        ], 400);
    });
});

Route::get('products/{filename}', function ($filename) {
    $path = base_path('public_html/products/' . $filename);
    if (!File::exists($path)) {
        abort(404, 'File not found');
    }
    return response()->file($path);
});

Route::get('videos/{filename}', function ($filename) {
    $path = base_path('public_html/videos/' . $filename);
    if (!File::exists($path)) {
        abort(404, 'File not found');
    }
    return response()->file($path);
});

Route::get('users/{filename}', function ($filename) {
    $path = base_path('public_html/users/' . $filename);
    if (!File::exists($path)) {
        abort(404, 'File not found');
    }
    return response()->file($path);;
});
