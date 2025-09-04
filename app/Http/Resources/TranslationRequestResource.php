<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource class for transforming translation request data to JSON.
 */
class TranslationRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request  The incoming HTTP request.
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'title' => $this->title,
            'description' => $this->description,
            'source_language' => $this->source_language,
            'target_language' => $this->target_language,
            'status' => $this->status,
            'original_content' => $this->original_content,
            'translated_content' => $this->translated_content,
            'error_message' => $this->when(
                $this->status === 'failed',
                $this->error_message
            ),
            'processed_at' => $this->processed_at?->toISOString(),
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];
    }
}
