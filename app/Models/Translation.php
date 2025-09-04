<?php

namespace App\Models;

use App\Enums\TranslationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Translation",
 *     type="object",
 *     title="Translation Model",
 *     properties={
 *
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="name", type="string", example="John Doe"),
 *         @OA\Property(property="title", type="string", example="Welcome Message"),
 *         @OA\Property(property="description", type="string", example="This is a welcome message."),
 *         @OA\Property(property="source_language", type="string", example="en"),
 *         @OA\Property(property="target_language", type="string", example="es"),
 *         @OA\Property(property="original_content", type="object"),
 *         @OA\Property(property="translated_content", type="object", nullable=true),
 *         @OA\Property(property="status", type="string", enum={"pending", "processing", "completed", "failed"}),
 *         @OA\Property(property="error_message", type="string", nullable=true),
 *         @OA\Property(property="processed_at", type="string", format="date-time", nullable=true),
 *         @OA\Property(property="created_at", type="string", format="date-time"),
 *         @OA\Property(property="updated_at", type="string", format="date-time")
 *     }
 * )
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
        'processed_at',
    ];

    protected $casts = [
        'original_content' => 'array',
        'translated_content' => 'array',
        'processed_at' => 'datetime',
        'status' => TranslationStatus::class,
    ];

    /**
     * Mark the translation request as processing.
     */
    public function markAsProcessing(): void
    {
        $this->update(['status' => TranslationStatus::Processing]);
    }

    /**
     * Mark the translation request as completed and set the translated content.
     *
     * @param  array  $translatedContent  The translated content to store.
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
     * @param  string  $errorMessage  The error message to store.
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
