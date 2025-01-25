<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;
use Google\Client;
use Google\Service\Analytics;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchAnalyticsData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        // Your logic to fetch data from Google Analytics API
        Log::info('FetchAnalyticsData job started.');

        // Simulating analytics data fetching logic
        $client = new Client();
        $client->setAuthConfig(storage_path('credentials/google_credentials.json'));

        $analytics = new Analytics($client);
        $accounts = $analytics->management_accounts->listManagementAccounts();

        Log::info('Analytics Data Fetched:', $accounts);
    }

    private function fetchAnalyticsData()
    {
        // Load Google Client
        $client = new Client();
        $client->setAuthConfig(storage_path('credentials/google_credentials.json'));
        $client->addScope(Analytics::ANALYTICS_READONLY);

        // Instantiate the Analytics service
        $analytics = new Analytics($client);

        // Fetch accounts
        $accounts = $analytics->management_accounts->listManagementAccounts();

        // Return fetched data
        $result = [];
        foreach ($accounts->getItems() as $account) {
            $result[] = [
                'id' => $account->getId(),
                'name' => $account->getName(),
                'created' => $account->getCreated(),
            ];
        }

        return $result;
    }

    public function triggerJob()
    {
        FetchAnalyticsData::dispatch();
        return response()->json(['message' => 'Job dispatched!']);
    }
}
