<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Reunion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\MeetingInvitationMail;

class ParticipantController extends Controller
{
    public function index()
    {
        $participants = User::where('role', '!=', 'admin')->with('instances')->orderBy('name')->get();
        return view('participants.index', compact('participants'));
    }

    public function create()
    {
        if (!Auth::user()->isAdmin()) return abort(403);
        $instances = \App\Models\Instance::all();
        return view('participants.create', compact('instances'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) return abort(403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:enseignant,fonctionnaire,responsable',
            'password' => 'required|string|min:6',
            'instances' => 'nullable|array',
            'instances.*' => 'exists:instances,id',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
        ]);

        if (!empty($validated['instances'])) {
            $user->instances()->attach($validated['instances']);
        }

        // Send Welcome Notification
        $user->notify(new \App\Notifications\MeetingNotification([
            'title' => 'Bienvenue !',
            'message' => "Votre compte a été créé. Vous pouvez maintenant gérer vos réunions.",
            'action_url' => route('dashboard'),
            'type' => 'account'
        ]));

        return redirect()->route('participants.index')->with('success', 'Participant ajouté avec succès.');
    }

    public function edit(User $participant)
    {
        if (!Auth::user()->isAdmin()) return abort(403);
        $instances = \App\Models\Instance::all();
        $participant->load('instances');
        return view('participants.edit', compact('participant', 'instances'));
    }

    public function update(Request $request, User $participant)
    {
        if (!Auth::user()->isAdmin()) return abort(403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $participant->id,
            'role' => 'required|in:enseignant,fonctionnaire,responsable',
            'password' => 'nullable|string|min:6',
            'instances' => 'nullable|array',
            'instances.*' => 'exists:instances,id',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
        }

        $oldInstanceIds = $participant->instances->pluck('id')->toArray();
        $participant->update($data);

        $newInstanceIds = $validated['instances'] ?? [];
        $participant->instances()->sync($newInstanceIds);

        // Notify if newly added to a commission
        $addedInstances = array_diff($newInstanceIds, $oldInstanceIds);
        if (!empty($addedInstances)) {
            $instanceNames = \App\Models\Instance::whereIn('id', $addedInstances)->pluck('nom')->implode(', ');
            $participant->notify(new \App\Notifications\MeetingNotification([
                'title' => 'Nouvelle Commission',
                'message' => "Vous avez été ajouté à : {$instanceNames}",
                'action_url' => route('dashboard'),
                'type' => 'assignment'
            ]));
        }

        return redirect()->route('participants.index')->with('success', 'Participant mis à jour.');
    }

    public function destroy(User $participant)
    {
        if (!Auth::user()->isAdmin()) return abort(403);

        $participant->delete();
        return redirect()->route('participants.index')->with('success', 'Participant supprimé.');
    }

    public function invite(Request $request, Reunion $reunion)
    {
        if (!Auth::user()->isAdmin()) {
            return abort(403);
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $reunion->participants()->syncWithoutDetaching([$validated['user_id']]);

        // Send Email
        $participant = User::find($validated['user_id']);
        Mail::to($participant->email)->send(new MeetingInvitationMail($reunion, $participant));

        // Send Database Notification
        $participant->notify(new \App\Notifications\MeetingNotification([
            'title' => 'Nouvelle invitation',
            'message' => "Vous avez été invité à rejoindre la réunion : {$reunion->titre}",
            'action_url' => route('reunions.show', $reunion),
            'type' => 'invitation'
        ]));

        return back()->with('success', 'Participant invité et notifications envoyées.');
    }

    public function updateStatus(Request $request, Reunion $reunion)
    {
        $status = $request->status;
        $reunion->participants()->updateExistingPivot(Auth::id(), [
            'response_status' => $status,
        ]);

        // Notify the creator
        $creator = $reunion->creator;
        if ($creator) {
            $statusFr = $status == 'accepter' ? 'accepté' : 'décliné';
            $creator->notify(new \App\Notifications\MeetingNotification([
                'title' => 'Réponse à une invitation',
                'message' => Auth::user()->name . " a {$statusFr} l'invitation pour : {$reunion->titre}",
                'action_url' => route('reunions.show', $reunion),
                'type' => 'response'
            ]));
        }

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
