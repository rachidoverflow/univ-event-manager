<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Reunion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgendaController extends Controller
{
    public function store(Request $request, Reunion $reunion)
    {
        if (!Auth::user()->isAdmin()) {
            return abort(403);
        }

        $validated = $request->validate([
            'titre' => 'required|string|max:200',
            'description' => 'nullable|string',
            'ordre' => 'nullable|integer',
        ]);

        $reunion->agendas()->create($validated);

        return back()->with('success', 'Point ajouté à l\'ordre du jour.');
    }

    public function destroy(Agenda $agenda)
    {
        if (!Auth::user()->isAdmin()) {
            return abort(403);
        }
        $agenda->delete();
        return back()->with('success', 'Point supprimé.');
    }
}
