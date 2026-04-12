<?php

namespace App\Http\Controllers;

use App\Models\Reunion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReunionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            $reunions = Reunion::with('creator')->latest()->get();
        } else {
            $reunions = $user->attendedReunions()->with('creator')->latest()->get();
        }
        return view('reunions.index', compact('reunions'));
    }

    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('reunions.index')->with('error', 'Accès refusé.');
        }
        return view('reunions.create');
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            return abort(403);
        }

        $validated = $request->validate([
            'titre' => 'required|string|max:200',
            'date' => 'required|date',
            'lieu' => 'nullable|string|max:200',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['status'] = 'planifiee';

        Reunion::create($validated);

        return redirect()->route('reunions.index')->with('success', 'Réunion créée avec succès.');
    }

    public function show(Reunion $reunion)
    {
        $reunion->load(['agendas', 'participants', 'compteRendu', 'creator']);
        return view('reunions.show', compact('reunion'));
    }

    public function edit(Reunion $reunion)
    {
        if (!Auth::user()->isAdmin()) {
            return abort(403);
        }
        return view('reunions.edit', compact('reunion'));
    }

    public function update(Request $request, Reunion $reunion)
    {
        if (!Auth::user()->isAdmin()) {
            return abort(403);
        }

        $validated = $request->validate([
            'titre' => 'required|string|max:200',
            'date' => 'required|date',
            'lieu' => 'nullable|string|max:200',
            'status' => 'required|in:planifiee,en_cours,terminee',
        ]);

        $reunion->update($validated);

        return redirect()->route('reunions.show', $reunion)->with('success', 'Réunion mise à jour.');
    }

    public function destroy(Reunion $reunion)
    {
        if (!Auth::user()->isAdmin()) {
            return abort(403);
        }
        $reunion->delete();
        return redirect()->route('reunions.index')->with('success', 'Réunion supprimée.');
    }
}
