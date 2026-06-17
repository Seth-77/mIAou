<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Tag;
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

        // On crée un jeu de tags partagés (rappel : les tags sont globaux)
        $tags = Tag::factory()->count(8)->create();

        // Pour chaque user, quelques conversations
        $users->push($test)->each(function (User $user) use ($tags) {
            Conversation::factory()
                ->count(rand(2, 5))
                ->for($user)
                ->create()
                ->each(function (Conversation $conversation) use ($tags) {
                    // Nombre de paires de messages (échanges)
                    $exchanges = rand(2, 6);

                    for ($i = 0; $i < $exchanges; $i++) {
                        Message::factory()->user()->for($conversation)->create();
                        Message::factory()->assistant()->for($conversation)->create();
                    }

                    // On attache 0 à 3 tags au hasard à cette conversation
                    // -> remplit la table pivot conversation_tag (relation N-N)
                    $conversation->tags()->attach(
                        $tags->random(rand(0, 3))->pluck('id')->toArray()
                    );
                });
        });
    }
}
