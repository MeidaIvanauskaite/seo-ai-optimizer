<?php

namespace App\Services;

use Google\Client;
use Google\Service\Analytics;
use Google_Service_Analytics;

class GoogleAnalyticsService
{

    protected $client;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setAuthConfig(storage_path('credentials/google_credentials.json'));
        $this->client->addScope('https://www.googleapis.com/auth/analytics.readonly');
    }

    public function getAnalyticsData()
    {
        $client = new Client();
        $client->setAuthConfig(config('services.google.key_file_path'));
        $client->addScope('https://www.googleapis.com/auth/analytics.readonly');

        $analytics = new Analytics($client);
        $accounts = $analytics->management_accounts->listManagementAccounts();
        return $accounts;
    }

    public function fetchAccounts()
    {
        $analytics = new Google_Service_Analytics($this->client);
        return $analytics->management_accounts->listManagementAccounts();
    }
}
