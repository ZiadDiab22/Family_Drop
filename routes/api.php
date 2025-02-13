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
use App\Http\Middleware\AllowNonMarketers;
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

Route::group(["middleware" => ["auth:api"]], function () {
    Route::post("addEmp", [UserController::class, "addEmp"])->middleware(adm_emp::class);
    Route::get("logout", [UserController::class, "logout"]);
    Route::post("editUserData", [UserController::class, "editUserData"]);
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
    Route::post("addAddresse", [AddressController::class, "addAddresse"])->middleware(adm_emp::class);
    Route::get("deleteAddresse/{id}", [AddressController::class, "deleteAddresse"])->middleware(adm_emp::class);
    Route::post("editAddresse", [AddressController::class, "editAddresse"])->middleware(adm_emp::class);
    Route::post("addOrderTag", [OrderController::class, "addOrderTag"])->middleware(mark::class);
    Route::post("addProduct", [ProductController::class, "addProduct"])->middleware(adm_emp::class);
    Route::post("editProduct", [ProductController::class, "editProduct"])->middleware(adm_emp::class);
    Route::get("deleteProduct/{id}", [ProductController::class, "deleteProduct"])->middleware(adm_emp::class);
    Route::post("addSize", [SizeController::class, "addSize"])->middleware(adm_emp::class);
    Route::post("addColor", [ColorController::class, "addColor"])->middleware(adm_emp::class);
    Route::post("addProductSize", [SizeController::class, "addProductSize"])->middleware(adm_emp::class);
    Route::post("addProductColor", [ColorController::class, "addProductColor"])->middleware(adm_emp::class);
    Route::post("editSize", [SizeController::class, "editSize"])->middleware(adm_emp::class);
    Route::post("editColor", [ColorController::class, "editColor"])->middleware(adm_emp::class);
    Route::get("blockUser/{id}", [UserController::class, "blockUser"])->middleware(adm_emp::class);
    Route::get("activatePaymentWay/{id}", [UserController::class, "activatePaymentWay"])->middleware(adm_emp::class);
    Route::get("showUsers", [UserController::class, "showUsers"])->middleware(adm_emp::class);
    Route::get("showRequests", [UserController::class, "showRequests"])->middleware(adm_emp::class);
    Route::get("showUserOrders", [OrderController::class, "showUserOrders"])->middleware(mark::class);
    Route::post("pullMoneyRequest", [RequestController::class, "pullMoneyRequest"])->middleware(mm::class);
    Route::get("acceptPullMoneyRequest/{id}", [RequestController::class, "acceptPullMoneyRequest"])->middleware(adm_emp::class);
    Route::get("showPullRequests", [RequestController::class, "showPullRequests"]);
    Route::get("deletePullRequest/{id}", [RequestController::class, "deletePullRequest"])->middleware(adm_emp::class);
    Route::post("addProductRequest", [RequestController::class, "addProductRequest"])->middleware(merch::class);
    Route::post("acceptAddProductRequest/{id}", [RequestController::class, "acceptAddProductRequest"])->middleware(adm_emp::class);
    Route::get("showProductRequests", [RequestController::class, "showProductRequests"])->middleware(AllowNonMarketers::class);
    Route::get("deleteProductRequest/{id}", [RequestController::class, "deleteProductRequest"])->middleware(adm_emp::class);
    Route::post("PullProductRequest", [RequestController::class, "PullProductRequest"])->middleware(merch::class);
    Route::get("acceptPullProductRequest/{id}", [RequestController::class, "acceptPullProductRequest"])->middleware(adm_emp::class);
    Route::get("showPullProductRequests", [RequestController::class, "showPullProductRequests"])->middleware(AllowNonMarketers::class);
    Route::get("deletePullProductRequest/{id}", [RequestController::class, "deletePullProductRequest"])->middleware(adm_emp::class);
    Route::post("addOrder", [OrderController::class, "addOrder"])->middleware(mark::class);
    Route::post("editCommission", [SettingController::class, "editCommission"])->middleware(adm_emp::class);
    Route::get("profile", [UserController::class, "profile"])->middleware(mm::class);
    Route::get("showUserInfo/{id}", [UserController::class, "showUserInfo"])->middleware(adm_emp::class);
});

Route::get('products/{filename}', function ($filename) {
    $path = base_path('public_html/products/' . $filename);
    if (!File::exists($path)) {
        abort(404, 'File not found');
    }
    return response()->file($path);;
});
