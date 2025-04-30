<?php

namespace App\Http\Integrations;

use Saloon\Http\Connector;

class PlantNetConnector extends Connector
{
    public function resolveBaseUrl(): string
    {
        return 'https://my-api.plantnet.org/v2';
    }

    protected function defaultHeaders(): array
    {
        return [
            'Accept' => 'application/json',
        ];
    }
}
