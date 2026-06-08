<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class InstructionsController extends Controller
{
    public function edit()
    {
        return Inertia::render('settings/Instructions', [
            'about_me' => Auth::user()->about_me,
            'assistant_instructions' => Auth::user()->assistant_instructions,
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'about_me' => 'nullable|string|max:2000',
            'assistant_instructions' => 'nullable|string|max:2000',
        ]);

        Auth::user()->update($validated);
        return back();
    }
}
