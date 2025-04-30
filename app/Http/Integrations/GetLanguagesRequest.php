<?php

namespace App\Http\Integrations;

use Saloon\Http\Request;
use Saloon\Enums\Method;
use Saloon\Traits\Plugins\HasTimeout;

class GetLanguagesRequest extends Request
{
    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::GET;

    use HasTimeout;

    protected int $connectTimeout = 60;
    protected int $requestTimeout = 120;

    /**
     * Define the API key as a constructor parameter
     */
    public function __construct(
        protected string $apiKey
    ) {
    }

    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/languages';
    }

    /**
     * Add API key as a query parameter
     */
    protected function defaultQuery(): array
    {
        return [
            'api-key' => $this->apiKey
        ];
    }

    /**
     * Define default headers
     */
    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }
}
