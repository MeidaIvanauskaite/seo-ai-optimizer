<?php

use Illuminate\Support\Facades\Route;
use App\Services\GoogleService;
use App\Http\Controllers\GoogleAnalyticsController;
use App\Jobs\FetchAnalyticsData;
use Illuminate\Support\Facades\Auth;

Route::get('/google-accounts', function (GoogleService $googleService) {
    try {
        $accounts = $googleService->getAnalyticsAccounts();
        return response()->json($accounts);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()]);
    }
});

Route::get('/google-accounts/{accountId}', [GoogleAnalyticsController::class, 'fetchWebProperties']);

Route::get('/google', [GoogleAnalyticsController::class, 'fetchAccountsWithProperties']);

Route::get('/trigger-job', function () {
    FetchAnalyticsData::dispatch();
    return response()->json(['message' => 'Job dispatched!']);
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
