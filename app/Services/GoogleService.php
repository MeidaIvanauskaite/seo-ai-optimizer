<?php

namespace App\Services;

use Google\Client;
use Google\Service\Analytics;

class GoogleService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setAuthConfig(config('services.google.service_account_file'));
        $this->client->addScope(Analytics::ANALYTICS_READONLY);
    }

    public function getAnalyticsAccounts()
    {
        $analytics = new Analytics($this->client);
        return $analytics->management_accounts->listManagementAccounts();
    }
}
