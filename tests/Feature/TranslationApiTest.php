<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Translation;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Feature tests for the Translation API endpoints.
 */
class TranslationApiTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Test that a translation request can be created successfully.
     *
     * @return void
     */
    public function testCanCreateTranslationRequest(): void
    {
        Queue::fake();

        $payload = [
            'name' => 'John Doe',
            'title' => 'Welcome Message',
            'description' => 'This is a welcome message for our users.',
            'target_language' => 'es'
        ];

        $response = $this->postJson('/api/v1/translations', $payload);

        $response->assertStatus(201)
            ->assertJson(
                [
                    'success' => true,
                    'message' => 'Translation request created successfully'
                ]
            );

        $this->assertDatabaseHas(
            'translations', [
                'name' => 'John Doe',
                'title' => 'Welcome Message',
                'status' => 'pending'
            ]
        );
    }

    /**
     * Test that validation fails when invalid data is provided.
     *
     * @return void
     */
    public function testValidationFailsWithInvalidData(): void
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
     *
     * @return void
     */
    public function testCanRetrieveTranslation(): void
    {
        $translation = Translation::factory()->create();

        $response = $this->getJson("/api/v1/translations/{$translation->id}");

        $response->assertStatus(200)
            ->assertJson(
                [
                    'success' => true,
                    'data' => [
                        'id' => $translation->id,
                        'name' => $translation->name
                    ]
                ]
            );
    }
}