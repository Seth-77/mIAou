<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Conversation>
 */
class ConversationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(4),
            'model' => fake()->randomElement([
                'openai/gpt-4o-mini',
                'google/gemini-flash-1.5',
                'meta-llama/llama-3.1-8b-instruct',
            ]),
        ];
    }
}
