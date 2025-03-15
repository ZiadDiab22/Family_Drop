<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return response()->json([
        'message' => 'Email verified successfully'
    ]);
})->middleware(['auth:api', 'signed'])->name('verification.verify');

Route::get('/', function () {
    return view('welcome');
});
