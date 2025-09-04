<?php

namespace App\Http\Integrations\OpenAIConnector;

use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;

/**
 * Connector for interacting with the OpenAI API for translation.
 */
class Translator extends Connector
{
    use AcceptsJson, AlwaysThrowOnErrors;

    /**
     * The Base URL of the API
     *
     * @return string
     */
    public function resolveBaseUrl(): string
    {
        return config('services.openai.base_url', 'https://api.openai.com/v1');
    }

    /**
     * Default headers for every request
     *
     * @return array
     */
    protected function defaultHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . config('services.openai.api_key'),
            'Content-Type' => 'application/json',
            // 'OpenAI-Project' => config('services.openai.project_id'),
        ];
    }

    /**
     * Default HTTP client options
     *
     * @return array
     */
    protected function defaultConfig(): array
    {
        return [];
    }
}