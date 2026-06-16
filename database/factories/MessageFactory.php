<?php

namespace Database\Factories;

use App\Models\Conversation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'conversation_id' => Conversation::factory(),
            'role' => fake()->randomElement(['user', 'assistant']),
            'content' => fake()->paragraph(),
        ];
    }

    // États pratiques pour forcer le rôle
    public function user(): static
    {
        return $this->state(['role' => 'user', 'content' => fake()->sentence()]);
    }

    public function assistant(): static
    {
        return $this->state(['role' => 'assistant', 'content' => fake()->paragraphs(2, true)]);
    }
}
