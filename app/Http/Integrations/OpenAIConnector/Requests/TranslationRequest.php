<?php

namespace App\Http\Integrations\OpenAIConnector\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Handles the construction of translation requests to the OpenAI API.
 *
 * This class prepares the request body and endpoint for translating JSON content
 * from a source language to a target language using OpenAI's chat completions endpoint.
 */
class TranslationRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * TranslationRequest constructor.
     *
     * @param  array  $content  The JSON content to be translated.
     * @param  string  $sourceLanguage  The source language code.
     * @param  string  $targetLanguage  The target language code.
     */
    public function __construct(
        private readonly array $content,
        private readonly string $sourceLanguage,
        private readonly string $targetLanguage
    ) {}

    /**
     * Returns the API endpoint for the translation request.
     */
    public function resolveEndpoint(): string
    {
        return '/chat/completions';
    }

    /**
     * Returns the default request body for the translation API call.
     *
     * @return array The request body containing model, messages, max_tokens, and temperature.
     */
    protected function defaultBody(): array
    {
        $contentString = json_encode($this->content);

        return [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $this->_getSystemPrompt(),
                ],
                [
                    'role' => 'user',
                    'content' => $contentString,
                ],
            ],
            'max_tokens' => 2000,
            'temperature' => 0.3,
        ];
    }

    /**
     * Generates the system prompt for the translation request.
     *
     * @return string The system prompt for the OpenAI API.
     */
    private function _getSystemPrompt(): string
    {
        $targetLangName = $this->_getLanguageName($this->targetLanguage);

        return 'You are a professional translator. Translate the given JSON content from '.
            "{$this->sourceLanguage} to {$targetLangName}. ".
            'Maintain the exact JSON structure and only translate the text values. '.
            'Preserve any HTML tags, special formatting, and maintain the professional tone. '.
            'Return only valid JSON with the same keys but translated values.';
    }

    /**
     * Returns the language name for a given language code.
     *
     * @param string $code The language code.

     * @return string The language name.
     */
    private function _getLanguageName(string $code): string
    {
        return match ($code) {
            'es' => 'Spanish',
            'fr' => 'French',
            'de' => 'German',
            'it' => 'Italian',
            'pt' => 'Portuguese',
            default => 'Spanish'
        };
    }
}
