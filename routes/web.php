<?php

use Illuminate\Support\Facades\Route;
use App\Services\GoogleService;
use App\Http\Controllers\GoogleAnalyticsController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/google-accounts', function (GoogleService $googleService) {
    try {
        $accounts = $googleService->getAnalyticsAccounts();
        return response()->json($accounts);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()]);
    }
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/google', [GoogleAnalyticsController::class, 'index']);
