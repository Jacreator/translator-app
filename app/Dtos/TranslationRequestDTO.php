<?php

namespace App\Dtos;

/**
 * Data Transfer Object for translation requests.
 *
 * Holds the necessary information for a translation operation,
 * including name, title, description, source language, and target language.
 */
readonly class TranslationRequestDTO
{
    /**
     * TranslationRequestDTO constructor.
     *
     * @param  string  $name  The name of the translation request.
     * @param  string  $title  The title of the translation request.
     * @param  string  $description  The description of the translation request.
     * @param  string  $sourceLanguage  The source language code.
     * @param  string  $targetLanguage  The target language code.
     */
    public function __construct(
        public string $name,
        public string $title,
        public string $description,
        public string $sourceLanguage = 'en',
        public string $targetLanguage = 'es'
    ) {}

    /**
     * Create a new TranslationRequestDTO instance from an array of request data.
     *
     * @param  array  $data  The request data containing translation fields.
     */
    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'],
            title: $data['title'],
            description: $data['description'],
            targetLanguage: $data['target_language'] ?? 'es'
        );
    }

    /**
     * Convert the DTO properties to an associative array.
     *
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'title' => $this->title,
            'description' => $this->description,
        ];
    }
}
