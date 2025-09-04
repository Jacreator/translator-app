<?php

namespace Tests\Feature;

use App\Models\Translation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

/**
 * Feature tests for the Translation API endpoints.
 */
class TranslationApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Test that a translation request can be created successfully.
     */
    public function test_can_create_translation_request(): void
    {
        Queue::fake();

        $payload = [
            'name' => 'John Doe',
            'title' => 'Welcome Message',
            'description' => 'This is a welcome message for our users.',
            'target_language' => 'es',
        ];

        $response = $this->postJson('/api/v1/translations', $payload);

        $response->assertStatus(201)
            ->assertJson(
                [
                    'success' => true,
                    'message' => 'Translation request created successfully',
                ]
            );

        $this->assertDatabaseHas(
            'translations', [
                'name' => 'John Doe',
                'title' => 'Welcome Message',
                'status' => 'pending',
            ]
        );
    }

    /**
     * Test that validation fails when invalid data is provided.
     */
    public function test_validation_fails_with_invalid_data(): void
    {
        $payload = [
            'name' => 'A', // Too short
            'title' => 'Hi', // Too short
            'description' => 'Short', // Too short
        ];

        $response = $this->postJson('/api/v1/translations', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'title', 'description']);
    }

    /**
     * Test that a translation request can be retrieved successfully.
     */
    public function test_can_retrieve_translation(): void
    {
        $translation = Translation::factory()->create();

        $response = $this->getJson("/api/v1/translations/{$translation->id}");

        $response->assertStatus(200)
            ->assertJson(
                [
                    'success' => true,
                    'data' => [
                        'id' => $translation->id,
                        'name' => $translation->name,
                    ],
                ]
            );
    }
}
