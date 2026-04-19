<?php

namespace App\Http\Controllers;

use App\Models\Instance;
use Illuminate\Http\Request;

class InstanceController extends Controller
{
    public function show(Instance $instance)
    {
        $instance->load('members');
        return view('instances.show', compact('instance'));
    }

    public function removeMember(Instance $instance, User $user)
    {
        if (!auth()->user()->isAdmin()) {
            return abort(403);
        }

        $instance->members()->detach($user->id);

        return back()->with('success', 'Membre retiré de la commission.');
    }
}
