<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Reunion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParticipantController extends Controller
{
    public function invite(Request $request, Reunion $reunion)
    {
        if (!Auth::user()->isAdmin()) {
            return abort(403);
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $reunion->participants()->syncWithoutDetaching([$validated['user_id']]);

        return back()->with('success', 'Participant invité.');
    }

    public function updateStatus(Request $request, Reunion $reunion)
    {
        $reunion->participants()->updateExistingPivot(Auth::id(), [
            'response_status' => $request->status,
        ]);

        return back()->with('success', 'Votre réponse a été enregistrée.');
    }

    public function markPresence(Request $request, Reunion $reunion, User $user)
    {
        if (!Auth::user()->isAdmin()) {
            return abort(403);
        }

        $reunion->participants()->updateExistingPivot($user->id, [
            'presence' => $request->presence,
        ]);

        return back()->with('success', 'Présence mise à jour.');
    }
}
