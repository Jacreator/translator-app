<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\TranslationStatus;

/**
 * Model representing a translation request, including source and target languages,
 * original and translated content, status, error messages, and processing
 * timestamps.
 */
class Translation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'title',
        'description',
        'source_language',
        'target_language',
        'original_content',
        'translated_content',
        'status',
        'error_message',
        'processed_at'
    ];

    protected $casts = [
        'original_content' => 'array',
        'translated_content' => 'array',
        'processed_at' => 'datetime',
        'status' => TranslationStatus::class,
    ];

    /**
     * Mark the translation request as processing.
     *
     * @return void
     */
    public function markAsProcessing(): void
    {
        $this->update(['status' => TranslationStatus::Processing]);
    }

    /**
     * Mark the translation request as completed and set the translated content.
     *
     * @param array $translatedContent The translated content to store.
     *
     * @return void
     */
    public function markAsCompleted(array $translatedContent): void
    {
        $this->update(
            [
                'status' => TranslationStatus::Completed,
                'translated_content' => $translatedContent,
                'processed_at' => now(),
            ]
        );
    }

    /**
     * Mark the translation request as failed and set the error message.
     *
     * @param string $errorMessage The error message to store.
     *
     * @return void
     */
    public function markAsFailed(string $errorMessage): void
    {
        $this->update(
            [
                'status' => TranslationStatus::Failed,
                'error_message' => $errorMessage,
                'processed_at' => now(),
            ]
        );
    }
}