<?php

namespace App\Services;

use App\Dtos\TranslationRequestDTO;
use App\Enums\TranslationStatus;
use App\Jobs\ProcessTranslationJob;
use App\Models\Translation;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

/**
 * Service class for handling translation requests and related operations.
 */
class TranslationService
{
    /**
     * Creates a new translation request and dispatches a job for processing.
     *
     * @param TranslationRequestDTO $dto Data transfer object containing translation
     *                                   request details.
     *
     * @return Translation The created translation request model instance.
     */
    public function createTranslationRequest(
        TranslationRequestDTO $dto
    ): Translation {
        return DB::transaction(
            function () use ($dto) {
                $translationRequest = Translation::create(
                    [
                        'name' => $dto->name,
                        'title' => $dto->title,
                        'description' => $dto->description,
                        'source_language' => $dto->sourceLanguage,
                        'target_language' => $dto->targetLanguage,
                        'original_content' => $dto->toArray(),
                        'status' => TranslationStatus::Pending,
                    ]
                );

                ProcessTranslationJob::dispatch($translationRequest);

                return $translationRequest;
            }
        );
    }

    /**
     * Retrieves a translation request by its ID.
     *
     * @param int $id The ID of the translation request.
     *
     * @return Translation|null The translation request model instance or
     *                          null if not found.
     */
    public function getTranslationRequest(int $id): ?Translation
    {
        return Translation::find($id);
    }

    /**
     * Retrieves a paginated list of translation requests, optionally filtered
     * by status and target language.
     *
     * @param array $filters Optional filters for status and target language.
     *
     * @return LengthAwarePaginator Paginated list of translation requests.
     */
    public function getTranslationRequests(array $filters = []): LengthAwarePaginator
    {
        $query = Translation::query()
            ->orderBy('created_at', 'desc');

        if (isset($filters['status'])) {
            $status = $filters['status'];
            if ($status instanceof TranslationStatus) {
                $query->where('status', $status);
            } elseif (is_string($status) && TranslationStatus::tryFrom($status)) {
                $query->where('status', TranslationStatus::from($status));
            } else {
                $query->where('status', $status);
            }
        }

        if (isset($filters['target_language'])) {
            $query->where('target_language', $filters['target_language']);
        }

        return $query->paginate(15);
    }
}