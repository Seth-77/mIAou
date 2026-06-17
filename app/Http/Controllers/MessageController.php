<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Services\SimpleAskService;
use App\Services\SimpleAskStreamService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

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


    /**
     * Version STREAMING de store() : la réponse de l'IA est envoyée au
     * navigateur morceau par morceau, tout en étant accumulée côté serveur
     * pour être sauvée en base à la fin.
     */
    public function stream(Request $request, Conversation $conversation, SimpleAskStreamService $service): StreamedResponse
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        // PHASE 1 : sauver le message de l'utilisateur
        $conversation->messages()->create([
            'role' => 'user',
            'content' => $validated['content'],
        ]);

        // Reconstruire tout l'historique au format API (comme dans store())
        $history = $conversation->messages()
            ->orderBy('created_at')
            ->get()
            ->map(fn ($m) => [
                'role' => $m->role,
                'content' => $m->content,
            ])
            ->all();

        $question = $validated['content'];

        return response()->stream(
            function () use ($conversation, $history, $question, $service): void {
                // Pas de limite de temps pour le streaming (réponse longue de l'IA)
                set_time_limit(0);

                // Variable qui accumulera la réponse complète, morceau par morceau
                $fullResponse = '';

                // PHASE 2 : streamer vers le navigateur ET accumuler côté serveur.
                // Le callback reçoit chaque morceau et le concatène dans $fullResponse.
                $service->streamAndCapture(
                    $history,
                    $conversation->model,
                    1.0,
                    function (string $chunk) use (&$fullResponse): void {
                        $fullResponse .= $chunk;
                    }
                );

                // PHASE 3 : le stream est terminé, $fullResponse contient toute la réponse.
                // On la sauve en base comme message de l'assistant.
                $conversation->messages()->create([
                    'role' => 'assistant',
                    'content' => $fullResponse,
                ]);

                // Générer le titre si c'est le premier échange
                if (is_null($conversation->title)) {
                    $conversation->update([
                        'title' => $this->generateTitle($question, $fullResponse, app(SimpleAskService::class), $conversation->model),
                    ]);
                }

                // Marquer la conversation comme récemment active
                $conversation->touch();
            },
            headers: [
                'Content-Type' => 'text/plain; charset=utf-8',
                'Cache-Control' => 'no-cache, no-store',
                'X-Accel-Buffering' => 'no',
            ]
        );
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
