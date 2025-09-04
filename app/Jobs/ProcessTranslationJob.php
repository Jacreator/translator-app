<?php

namespace App\Jobs;

use Exception;
use App\Models\Translation;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Http\Integrations\OpenAIConnector\Translator;
use App\Http\Integrations\OpenAIConnector\Requests\TranslationRequest;

/**
 * Job responsible for processing translation requests using OpenAI and updating their status.
 */
class ProcessTranslationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $maxExceptions = 2;
    public int $timeout = 120;

    /**
     * Create a new job instance.
     *
     * @param Translation $translation The translation model instance.
     */
    public function __construct(
        public readonly Translation $translation
    ) {}

    /**
     * Execute the job to process the translation request.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->translation->markAsProcessing();

        $connector = new Translator();
        $request = new TranslationRequest(
            content: $this->translation->original_content,
            sourceLanguage: $this->translation->source_language,
            targetLanguage: $this->translation->target_language
        );

        $response = $connector->send($request);
        $responseData = $response->json();

        if (!isset($responseData['choices'][0]['message']['content'])) {
            throw new Exception('Invalid response format from OpenAI');
        }

        $translatedContent = json_decode(
            $responseData['choices'][0]['message']['content'],
            true
        );

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception(
                'Invalid JSON in OpenAI response: ' . json_last_error_msg()
            );
        }

        $this->translation->markAsCompleted($translatedContent);

        Log::info(
            'Translation completed',
            [
                'request_id' => $this->translation->id,
                'target_language' => $this->translation->target_language
            ]
        );
    }

    /**
     * Handle a job failure after all retry attempts.
     *
     * @param \Throwable $exception The exception thrown after job failure.
     *
     * @return void
     */
    public function failed(\Throwable $exception): void
    {
        Log::error(
            'Translation job failed after all retries: ' . $exception->getMessage(),
            [
                'request_id' => $this->translation->id,
                'attempts' => $this->attempts()
            ]
        );

        $this->translation->markAsFailed($exception->getMessage());
    }
}