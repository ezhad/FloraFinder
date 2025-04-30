<?php

namespace App\Http\Integrations;

use Saloon\Http\Request;
use Saloon\Enums\Method;
use Saloon\Traits\Plugins\HasTimeout;

class CheckStatusRequest extends Request
{
    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::GET;

    use HasTimeout;

    protected int $connectTimeout = 60;
    protected int $requestTimeout = 120;

    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/_status';
    }

    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }
}
