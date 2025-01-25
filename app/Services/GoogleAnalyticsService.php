<?php

namespace App\Services;

use Google\Client;
use Google\Service\Analytics;

class GoogleAnalyticsService
{
    public function getAnalyticsData()
    {
        $client = new Client();
        $client->setAuthConfig(config('services.google.key_file_path'));
        $client->addScope('https://www.googleapis.com/auth/analytics.readonly');

        $analytics = new Analytics($client);
        $accounts = $analytics->management_accounts->listManagementAccounts();
        return $accounts;
    }
}
