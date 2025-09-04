<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Dtos\TranslationRequestDTO;
use App\Jobs\ProcessTranslationJob;
use App\Services\TranslationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;

/**
 * Unit tests for the TranslationService class.
 */
class TranslationServiceTest extends TestCase
{
    use RefreshDatabase;

    private TranslationService $_service;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample(): void
    {
        $this->assertTrue(true);
    }

    /**
     * Set up the test environment before each test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->_service = $this->app->make(TranslationService::class);
    }

    /**
     * Test that a translation request is created and the job is dispatched.
     *
     * @return void
     */
    public function testCreatesTranslationRequestAndDispatchesJob(): void
    {
        Queue::fake();

        $dto = new TranslationRequestDTO(
            name: 'Test User',
            title: 'Test Title',
            description: 'Test description for translation',
            targetLanguage: 'es'
        );

        $result = $this->_service->createTranslationRequest($dto);

        $this->assertDatabaseHas(
            'translations',
            [
                'name' => 'Test User',
                'title' => 'Test Title',
                'description' => 'Test description for translation',
                'target_language' => 'es',
                'status' => 'pending',
            ]
        );

        Queue::assertPushedOn(
            'translations',
            ProcessTranslationJob::class,
            fn($job) => $job->translation->is($result)
        );
    }
}