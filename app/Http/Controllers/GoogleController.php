<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class GoogleController extends Controller
{
    public function showAnalyticsData()
    {
        // Example static values or replace this with your actual API call
        $accountName = "seo-ai-analytic-account";  // This should be dynamically fetched if necessary
        $accountId = "341721634";  // This should be dynamically fetched if necessary

        // Pass the data to the view
        return view('google', compact('accountName', 'accountId'));
    }
}
