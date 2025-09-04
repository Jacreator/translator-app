<?php

namespace Database\Factories;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory for creating Translation model instances.
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Translation>
 */
class TranslationFactory extends Factory
{
    protected $model = Translation::class;

    /**
     * Get the default state definition for the TranslationRequest model.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(3),
            'source_language' => 'en',
            'target_language' => $this->faker->randomElement(['es', 'fr', 'de']),
            'original_content' => [
                'name' => $this->faker->name(),
                'title' => $this->faker->sentence(4),
                'description' => $this->faker->paragraph(3)
            ],
            'status' => 'pending'
        ];
    }

    /**
     * Set the state of the translation request as completed with translated content.
     *
     * @return static
     */
    public function completed(): static
    {
        return $this->state(
            fn() => [
                'status' => 'completed',
                'translated_content' => [
                    'name' => 'Nombre Traducido',
                    'title' => 'Título Traducido',
                    'description' => 'Descripción traducida al español'
                ],
                'processed_at' => now()
            ]
        );
    }
}