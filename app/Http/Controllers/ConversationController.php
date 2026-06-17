<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Services\SimpleAskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Tag;

class ConversationController extends Controller
{
    public function index(Request $request, SimpleAskService $service)
    {
        // L'id du tag sur lequel filtrer (présent dans l'URL ?tag=...), ou null
        $activeTag = $request->integer('tag') ?: null;

        $conversations = Auth::user()->conversations()
            ->with('tags')                          // eager loading : on charge les tags d'un coup
            ->when($activeTag, function ($query) use ($activeTag) {
                // Si un tag est actif, on ne garde que les conversations qui l'ont
                $query->whereHas('tags', fn ($q) => $q->where('tags.id', $activeTag));
            })
            ->latest()
            ->get();

        return Inertia::render('Chat/Index', [
            'conversations' => $conversations,
            'currentConversation' => null,
            'messages' => [],
            'models' => $service->getModels(),
            'tags' => Tag::orderBy('name')->get(),   // tous les tags pour la barre de filtres
            'activeTag' => $activeTag,               // pour surligner le filtre actif
        ]);
    }

    public function show(int $id, SimpleAskService $service)
    {
        $conversation = Auth::user()->conversations()->with('tags')->findOrFail($id);
        $messages = $conversation->messages()
            ->orderBy('updated_at')
            ->get();

        return Inertia::render('Chat/Index', [
            'conversations' => Auth::user()->conversations()->with('tags')->latest()->get(),
            'currentConversation' => $conversation,
            'messages' => $messages,
            'models' => $service->getModels(),
            'tags' => Tag::orderBy('name')->get(),
            'activeTag' => null,
        ]);
    }

    public function store(SimpleAskService $service)
    {
        $conversation = Auth::user()->conversations()->create([
            'title' => null,
            'model' => Auth::user()->selected_model ?? SimpleAskService::DEFAULT_MODEL,
        ]);

        return redirect()->route('chat.show', $conversation);
    }

    public function updateModel(Request $request, Conversation $conversation)
    {
        $validated = $request->validate([
            'model' => 'required|string',
        ]);

        $conversation->update(['model' => $validated['model']]);
        Auth::user()->update(['selected_model' => $validated['model']]);

        return back();
    }

    public function destroy(Conversation $conversation)
    {
        $conversation->delete();

        return redirect()->route('chat.index');
    }
}
