<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Utilisateur de test fixe
        $test = User::factory()->create([
            'name'  => 'Test User',
            'email' => 'test@example.com',
        ]);

        // 10 users fictifs en plus
        $users = User::factory()->count(10)->create();

        // Pour chaque user, quelques conversations
        $users->push($test)->each(function (User $user) {
            Conversation::factory()
                ->count(rand(2, 5))
                ->for($user)
                ->create()
                ->each(function (Conversation $conversation) {
                    // Nombre de paires de messages (échanges)
                    $exchanges = rand(2, 6);

                    for ($i = 0; $i < $exchanges; $i++) {
                        Message::factory()->user()->for($conversation)->create();
                        Message::factory()->assistant()->for($conversation)->create();
                    }
                });
        });
    }
}
