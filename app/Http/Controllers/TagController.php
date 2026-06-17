<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    // Coller un tag sur une conversation
    public function attach(Request $request, Conversation $conversation)
    {
        $validated = $request->validate([
            'tag_id' => 'required|exists:tags,id',
        ]);

        $conversation->tags()->syncWithoutDetaching([$validated['tag_id']]);

        return back();
    }

    // Retirer un tag d'une conversation
    public function detach(Conversation $conversation, Tag $tag)
    {
        $conversation->tags()->detach($tag->id);

        return back();
    }
    // Créer un nouveau tag
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:tags,name',
        ]);

        Tag::create($validated);

        return back();
    }
}
