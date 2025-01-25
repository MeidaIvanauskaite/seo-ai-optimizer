<?php

namespace App\Http\Controllers;

use Google_Client;
use Google\Service\Analytics;
use App\Jobs\FetchAnalyticsData;

class GoogleController extends Controller
{
    private $client;

    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setAuthConfig(storage_path('credentials/google_credentials.json'));
        $this->client->addScope(Analytics::ANALYTICS_READONLY);
        $this->client->setAccessType('offline');
    }

    public function getAnalyticsAccounts()
    {
        $analyticsService = new Analytics($this->client);

        try {
            $accounts = $analyticsService->management_accounts->listManagementAccounts();

            $data = [];
            foreach ($accounts->getItems() as $account) {
                // Get web properties for the account
                $webProperties = $analyticsService->management_webproperties->listManagementWebproperties($account->getId());

                $data[] = [
                    'name' => $account->getName(),
                    'id' => $account->getId(),
                    'permissions' => $account->getPermissions(),
                    'childLink' => $account->getChildLink(),
                    'webProperties' => $webProperties->getItems() ?? [], // Handle empty web properties
                ];
            }

            // Pass the parsed data to the view
            return view('google', ['accounts' => $data]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function getAnalyticsAccountsAsJson()
    {
        $analyticsService = new Analytics($this->client);

        try {
            $accounts = $analyticsService->management_accounts->listManagementAccounts();
            $data = [];

            foreach ($accounts->getItems() as $account) {
                $webProperties = $analyticsService->management_webproperties->listManagementWebproperties($account->getId());

                $data[] = [
                    'name' => $account->getName(),
                    'id' => $account->getId(),
                    'permissions' => $account->getPermissions(),
                    'childLink' => $account->getChildLink(),
                    'webProperties' => $webProperties->getItems(),
                ];
            }

            return response()->json($data);  // Return data as JSON

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function showGoogleAccounts()
    {
        try {
            // Fetch the accounts as JSON using the same logic as before
            $response = file_get_contents('http://localhost/google-accounts');
            $accounts = json_decode($response, true);

            // Check if the 'items' key exists and has data
            if (isset($accounts['items']) && count($accounts['items']) > 0) {
                // Pass only the 'items' array to the view
                return view('google', ['accounts' => $accounts['items']]);
            } else {
                return view('google', ['accounts' => []]);  // Return an empty array if no data
            }

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function triggerJob()
    {
        FetchAnalyticsData::dispatch();
        return response()->json(['message' => 'Job dispatched!']);
    }
}
