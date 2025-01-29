<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\ColorController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SizeController;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\adm_emp;
use App\Http\Middleware\mark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post("register", [UserController::class, "register"]);
Route::post("login", [UserController::class, "login"]);

Route::group(["middleware" => ["auth:api"]], function () {
    Route::get("logout", [UserController::class, "logout"]);
    Route::post("editUserData", [UserController::class, "editUserData"]);
    Route::post("addCountry", [CountryController::class, "addCountry"])->middleware(adm_emp::class);
    Route::get("deleteCountry/{id}", [CountryController::class, "deleteCountry"])->middleware(adm_emp::class);
    Route::post("editCountry", [CountryController::class, "editCountry"])->middleware(adm_emp::class);
    Route::get("showCountries", [CountryController::class, "showCountries"])->middleware(adm_emp::class);
    Route::post("addProductType", [ProductController::class, "addProductType"])->middleware(adm_emp::class);
    Route::get("deleteProductType/{id}", [ProductController::class, "deleteProductType"])->middleware(adm_emp::class);
    Route::post("editProductType", [ProductController::class, "editProductType"])->middleware(adm_emp::class);
    Route::get("showProductTypes", [ProductController::class, "showProductTypes"])->middleware(adm_emp::class);
    Route::post("addCity", [CityController::class, "addCity"])->middleware(adm_emp::class);
    Route::get("deleteCity/{id}", [CityController::class, "deleteCity"])->middleware(adm_emp::class);
    Route::post("editCity", [CityController::class, "editCity"])->middleware(adm_emp::class);
    Route::get("showCities", [CityController::class, "showCities"])->middleware(adm_emp::class);
    Route::post("addAddresse", [AddressController::class, "addAddresse"])->middleware(adm_emp::class);
    Route::get("deleteAddresse/{id}", [AddressController::class, "deleteAddresse"])->middleware(adm_emp::class);
    Route::post("editAddresse", [AddressController::class, "editAddresse"])->middleware(adm_emp::class);
    Route::get("showAddresses", [AddressController::class, "showAddresses"])->middleware(adm_emp::class);
    Route::get("showLocations", [AddressController::class, "showLocations"]);
    Route::post("addOrderTag", [OrderController::class, "addOrderTag"])->middleware(mark::class);
    Route::post("addProduct", [ProductController::class, "addProduct"])->middleware(adm_emp::class);
    Route::get("showProducts", [ProductController::class, "showProducts"]);
    Route::get("deleteProduct/{id}", [ProductController::class, "deleteProduct"])->middleware(adm_emp::class);
    Route::post("addSize", [SizeController::class, "addSize"])->middleware(adm_emp::class);
    Route::post("addColor", [ColorController::class, "addColor"])->middleware(adm_emp::class);
    Route::post("addProductSize", [SizeController::class, "addProductSize"])->middleware(adm_emp::class);
    Route::post("addProductColor", [ColorController::class, "addProductColor"])->middleware(adm_emp::class);
    Route::post("editSize", [SizeController::class, "editSize"])->middleware(adm_emp::class);
    Route::post("editColor", [ColorController::class, "editColor"])->middleware(adm_emp::class);
    Route::get("showSizes", [SizeController::class, "showSizes"])->middleware(adm_emp::class);
    Route::get("showColors", [ColorController::class, "showColors"])->middleware(adm_emp::class);
    Route::get("showProductSizes/{id}", [SizeController::class, "showProductSizes"])->middleware(adm_emp::class);
    Route::get("showProductColors/{id}", [ColorController::class, "showProductColors"])->middleware(adm_emp::class);
    Route::get("showTypesSizesColors", [ProductController::class, "showTypesSizesColors"])->middleware(adm_emp::class);
});

Route::get('products/{filename}', function ($filename) {
    $path = base_path('public_html/products/' . $filename);
    if (!File::exists($path)) {
        abort(404, 'File not found');
    }
    return response()->file($path);;
});
