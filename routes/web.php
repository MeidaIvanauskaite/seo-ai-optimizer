<?php

use Illuminate\Support\Facades\Route;
use App\Services\GoogleService;
use App\Http\Controllers\GoogleAnalyticsController;
use App\Jobs\FetchAnalyticsData;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/google-accounts', function (GoogleService $googleService) {
    try {
        $accounts = $googleService->getAnalyticsAccounts();
        return response()->json($accounts);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()]);
    }
});

Route::get('/google', [GoogleAnalyticsController::class, 'index']);

Route::get('/trigger-job', function () {
    FetchAnalyticsData::dispatch();
    return response()->json(['message' => 'Job dispatched!']);
});
