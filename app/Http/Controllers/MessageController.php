<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Services\SimpleAskService;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function store(Request $request, Conversation $conversation, SimpleAskService $service)
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        // 1. Sauver le message de l'utilisateur
        $conversation->messages()->create([
            'role' => 'user',
            'content' => $validated['content'],
        ]);

        // 2. Reconstruire tout l'historique au format API
        $history = $conversation->messages()
            ->orderBy('created_at')
            ->get()
            ->map(fn ($m) => [
                'role' => $m->role,
                'content' => $m->content,
            ])
            ->all();

        // 3. Appeler l'API avec gestion d'erreur
        try {
            $answer = $service->sendMessage($history, $conversation->model);
        } catch (\Throwable $e) {
            return back()->withErrors([
                'content' => "Erreur du modèle : {$e->getMessage()}. Essaie un autre modèle.",
            ]);
        }

        // 4. Sauver la réponse de l'IA
        $conversation->messages()->create([
            'role' => 'assistant',
            'content' => $answer,
        ]);

        // 5. Générer le titre 
        if (is_null($conversation->title)) {
            $conversation->update([
                'title' => $this->generateTitle($validated['content'], $answer, $service, $conversation->model),
            ]);
        }

        // 6. Marquer la conversation comme récemment active
        $conversation->touch();

        return back();
    }

    private function generateTitle(string $question, string $answer, SimpleAskService $service, string $model): string
    {
        $prompt = [
            [
                'role' => 'user',
                'content' => "Résume cette conversation en un titre court (5 mots maximum), sans guillemets ni ponctuation finale.\n\n"
                    . "Question : {$question}\n"
                    . "Réponse : {$answer}",
            ],
        ];

        try {
            $title = trim($service->sendMessage($prompt, $model));
            return mb_substr($title, 0, 80);
        } catch (\Throwable $e) {
            return mb_substr($question, 0, 50);
        }
    }
}