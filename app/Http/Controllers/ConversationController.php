<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Services\SimpleAskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ConversationController extends Controller
{
    public function index(SimpleAskService $service)
    {
        return Inertia::render('Chat/Index', [
            'conversations' => Auth::user()->conversations()->latest()->get(),
            'currentConversation' => null,
            'messages' => [],
            'models' => $service->getModels(),
        ]);
    }

    public function show(Conversation $conversation, SimpleAskService $service)
    {
        $messages = $conversation->messages()
            ->orderBy('updated_at')
            ->get();

        return Inertia::render('Chat/Index', [
            'conversations' => Auth::user()->conversations()->latest()->get(),
            'currentConversation' => $conversation,
            'messages' => $messages,
            'models' => $service->getModels(),
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
