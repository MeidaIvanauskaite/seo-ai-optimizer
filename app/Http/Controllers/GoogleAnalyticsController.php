<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Google\Client;
use Google\Service\GoogleAnalyticsAdmin;

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

    public function fetchWebProperties($accountId) {
        $client = new Client();
        $client->setAuthConfig(storage_path('credentials/google_credentials.json'));
        $client->addScope('https://www.googleapis.com/auth/analytics.readonly');
        $adminService = new GoogleAnalyticsAdmin($client);

        try {
            $properties = $adminService->properties->listProperties([
                'filter' => 'parent:accounts/' . $accountId
            ]);

            return response()->json($properties->toSimpleObject());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function fetchAccountsWithProperties()
    {
        $client = new Client();
        $client->setAuthConfig(storage_path('credentials/google_credentials.json'));
        $client->addScope('https://www.googleapis.com/auth/analytics.readonly');

        $adminService = new GoogleAnalyticsAdmin($client);

        try {
            // Fetch Accounts
            $accounts = $adminService->accounts->listAccounts();
            $accountsWithProperties = [];

            // Fetch Properties for Each Account
            foreach ($accounts->getAccounts() as $account) {
                $properties = $adminService->properties->listProperties([
                    'filter' => 'parent:' . $account->getName(),
                ]);

                $accountsWithProperties[] = [
                    'account' => [
                        'id' => $account->getName(),
                        'name' => $account->getDisplayName(),
                    ],
                    'properties' => $properties->getProperties(),
                ];
            }

            // Pass the data to the Blade template
            return view('google', ['accounts' => $accountsWithProperties]);
        } catch (\Exception $e) {
            return view('google', ['accounts' => [], 'error' => $e->getMessage()]);
        }
    }
}
