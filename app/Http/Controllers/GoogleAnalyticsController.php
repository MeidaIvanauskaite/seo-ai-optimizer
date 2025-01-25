<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class GoogleAnalyticsController extends Controller
{
    public function index()
    {
        $pythonServiceUrl = env('PYTHON_SERVICE_URL', 'http://python_service:5000/analytics');
        $response = Http::get($pythonServiceUrl);
        $analyticsData = $response->json();

        return view('google', ['analytics' => $analyticsData]);
    }

    public function showGoogleAnalytics()
    {
        // Fetch Google Analytics data (you need to make sure this is working correctly)
        $analyticsData = $this->getGoogleAnalyticsAccounts();

        // Check if we received the expected data
        if (!empty($analyticsData['items'])) {
            $account = $analyticsData['items'][0]; // Get the first account
            $accountName = $account['name']; // Account name
            $accountId = $account['id']; // Account ID
        } else {
            $accountName = 'No accounts found';
            $accountId = 'N/A';
        }

        // Pass data to view
        return view('google', [
            'accountName' => $accountName,  // Make sure this variable is passed
            'accountId' => $accountId,  // Account ID passed correctly
            'data' => [
                'sessions' => 120,  // Replace with real data from the API
                'users' => 80,  // Replace with real data from the API
                'bounceRate' => 15,  // Replace with real data from the API
            ],
        ]);
    }
}
