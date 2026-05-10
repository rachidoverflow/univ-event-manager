<?php

namespace App\Http\Controllers;

use App\Models\Instance;
use Illuminate\Http\Request;

class InstanceController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isAdmin()) {
            return abort(403);
        }
        $instances = Instance::withCount('members')->get();
        return view('instances.index', compact('instances'));
    }

    public function create()
    {
        if (!auth()->user()->isAdmin()) {
            return abort(403);
        }
        return view('instances.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return abort(403);
        }

        $request->validate([
            'nom' => 'required|string|max:255|unique:instances',
            'description' => 'nullable|string',
        ]);

        Instance::create($request->all());

        return redirect()->route('instances.index')->with('success', 'Commission créée avec succès.');
    }

    public function show(Instance $instance)
    {
        $instance->load('members.user');
        $all_users = \App\Models\User::where('role', '!=', 'admin')->get();
        return view('instances.show', compact('instance', 'all_users'));
    }

    public function edit(Instance $instance)
    {
        if (!auth()->user()->isAdmin()) {
            return abort(403);
        }
        return view('instances.edit', compact('instance'));
    }

    public function update(Request $request, Instance $instance)
    {
        if (!auth()->user()->isAdmin()) {
            return abort(403);
        }

        $request->validate([
            'nom' => 'required|string|max:255|unique:instances,nom,' . $instance->id,
            'description' => 'nullable|string',
        ]);

        $instance->update($request->all());

        return redirect()->route('instances.index')->with('success', 'Commission mise à jour.');
    }

    public function destroy(Instance $instance)
    {
        if (!auth()->user()->isAdmin()) {
            return abort(403);
        }

        $instance->delete();

        return redirect()->route('instances.index')->with('success', 'Commission supprimée.');
    }

    public function addMember(Request $request, Instance $instance)
    {
        if (!auth()->user()->isAdmin()) {
            return abort(403);
        }

        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'guest_name' => 'nullable|string|max:255',
            'guest_email' => 'nullable|email|max:255',
        ]);

        if ($request->user_id) {
            if (!$instance->members()->where('user_id', $request->user_id)->exists()) {
                $instance->members()->create(['user_id' => $request->user_id]);
            }
            return back()->with('success', 'Membre ajouté à la commission.');
        } 
        
        if ($request->guest_name && $request->guest_email) {
            $instance->members()->create([
                'guest_name' => $request->guest_name,
                'guest_email' => $request->guest_email,
            ]);
            return back()->with('success', 'Invité externe ajouté à la commission.');
        }

        return back()->with('error', 'Veuillez sélectionner un membre ou remplir les informations de l\'invité.');
    }

    public function removeMember(Instance $instance, \App\Models\InstanceMember $member)
    {
        if (!auth()->user()->isAdmin()) {
            return abort(403);
        }

        $member->delete();

        return back()->with('success', 'Membre retiré de la commission.');
    }
    public function getMembers(Instance $instance)
    {
        $instance->load('members.user');
        return response()->json($instance->members);
    }
}
