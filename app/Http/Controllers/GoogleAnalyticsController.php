<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class GoogleAnalyticsController extends Controller
{
    public function index()
    {
        $response = Http::get('http://python_service:5000/analytics');
        $analyticsData = $response->json();

        return view('google', ['analytics' => $analyticsData]);
    }

    public function showGoogleAnalytics()
    {
        $accounts = $this->getGoogleAnalyticsAccounts(); // Fetch accounts from API
        $account = $accounts[0]; // Example: First account
        return view('google', [
            'accountName' => $account['name'],
            'accountId' => $account['id'],
            'data' => [
                'sessions' => 120, // Replace with actual data
                'users' => 80,
                'bounceRate' => 15,
            ],
        ]);
    }

}
