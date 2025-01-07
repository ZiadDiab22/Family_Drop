<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post("register", [UserController::class, "register"]);
Route::post("login", [UserController::class, "login"]);

Route::group(["middleware" => ["auth:api"]], function () {
    Route::get("logout", [UserController::class, "logout"]);
    Route::post("addCountry", [CountryController::class, "addCountry"]);
    Route::get("deleteCountry/{id}", [CountryController::class, "deleteCountry"]);
    Route::post("editCountry", [CountryController::class, "editCountry"]);
    Route::get("showCountries", [CountryController::class, "showCountries"]);
    Route::post("addProductType", [ProductController::class, "addProductType"]);
    Route::get("deleteProductType/{id}", [ProductController::class, "deleteProductType"]);
    Route::post("editProductType", [ProductController::class, "editProductType"]);
    Route::get("showProductTypes", [ProductController::class, "showProductTypes"]);
    Route::post("addCity", [CityController::class, "addCity"]);
    Route::get("deleteCity/{id}", [CityController::class, "deleteCity"]);
    Route::post("editCity", [CityController::class, "editCity"]);
    Route::get("showCities", [CityController::class, "showCities"]);
    Route::post("addAddresse", [AddressController::class, "addAddresse"]);
    Route::get("deleteAddresse/{id}", [AddressController::class, "deleteAddresse"]);
    Route::post("editAddresse", [AddressController::class, "editAddresse"]);
    Route::get("showAddresses", [AddressController::class, "showAddresses"]);
});
