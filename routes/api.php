<?php

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
    Route::post("logout", [UserController::class, "logout"]);
    Route::post("addCountry", [CountryController::class, "addCountry"]);
    Route::get("deleteCountry/{id}", [CountryController::class, "deleteCountry"]);
    Route::post("editCountry", [CountryController::class, "editCountry"]);
    Route::get("showCountries", [CountryController::class, "showCountries"]);
    Route::post("addProductClassify", [ProductController::class, "addProductClassify"]);
    Route::get("deleteProductClassify/{id}", [ProductController::class, "deleteProductClassify"]);
    Route::post("editProductClassify", [ProductController::class, "editProductClassify"]);
    Route::get("showProductClassifies", [ProductController::class, "showProductClassifies"]);
    Route::post("addProductType", [ProductController::class, "addProductType"]);
    Route::get("deleteProductType/{id}", [ProductController::class, "deleteProductType"]);
    Route::post("editProductType", [ProductController::class, "editProductType"]);
    Route::get("showProductTypes", [ProductController::class, "showProductTypes"]);
});
