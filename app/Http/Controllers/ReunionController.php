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
        if ($user->isAdmin() || $user->isResponsable()) {
            $reunions = Reunion::with('creator', 'instance')->latest()->get();
        } else {
            $reunions = $user->attendedReunions()->with('creator', 'instance')->latest()->get();
        }
        return view('reunions.index', compact('reunions'));
    }

    public function create()
    {
        if (!Auth::user()->isResponsable()) {
            return redirect()->route('reunions.index')->with('error', 'Accès refusé.');
        }
        $instances = \App\Models\Instance::all();
        $users = User::where('role', '!=', 'admin')->orderBy('name')->get();
        return view('reunions.create', compact('instances', 'users'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isResponsable()) {
            return abort(403);
        }

        $validated = $request->validate([
            'titre' => 'required|string|max:200',
            'date' => 'required|date',
            'lieu' => 'nullable|string|max:200',
            'instance_id' => 'nullable|exists:instances,id',
            'type' => 'required|in:standard,elargie',
            'invitation_content' => 'nullable|string',
            'extra_participants' => 'nullable|array',
            'extra_participants.*' => 'exists:users,id',
            'agenda' => 'nullable|array',
            'agenda.*.titre' => 'required|string|max:200',
            'agenda.*.description' => 'nullable|string',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['status'] = 'planifiee';

        $reunion = Reunion::create($validated);

        // 0. Add Agenda items
        if ($request->has('agenda')) {
            foreach ($request->agenda as $index => $item) {
                $reunion->agendas()->create([
                    'titre' => $item['titre'],
                    'description' => $item['description'],
                    'ordre' => $index
                ]);
            }
        }

        // 1. Auto-add instance members
        if ($reunion->instance_id) {
            $members = $reunion->instance->members;
            foreach ($members as $member) {
                $reunion->participants()->syncWithoutDetaching([
                    $member->id => ['response_status' => 'pending']
                ]);
                // Send invitation email
                \Illuminate\Support\Facades\Mail::to($member->email)->send(new \App\Mail\MeetingInvitationMail($reunion, $member));
                
                // Send Database Notification
                $member->notify(new \App\Notifications\MeetingNotification([
                    'title' => 'Nouvelle invitation',
                    'message' => "Vous êtes invité à la réunion : {$reunion->titre}",
                    'action_url' => route('reunions.show', $reunion),
                    'type' => 'invitation'
                ]));
            }
        }

        // 2. Add extra participants if Élargie
        if ($request->type === 'elargie' && $request->has('extra_participants')) {
            foreach ($request->extra_participants as $userId) {
                $user = User::find($userId);
                $reunion->participants()->syncWithoutDetaching([
                    $user->id => ['response_status' => 'pending']
                ]);
                // Send invitation email
                \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\MeetingInvitationMail($reunion, $user));

                // Send Database Notification
                $user->notify(new \App\Notifications\MeetingNotification([
                    'title' => 'Nouvelle invitation (élargie)',
                    'message' => "Vous avez été ajouté à la réunion : {$reunion->titre}",
                    'action_url' => route('reunions.show', $reunion),
                    'type' => 'invitation'
                ]));
            }
        }

        return redirect()->route('reunions.index')->with('success', 'Réunion planifiée et invitations envoyées.');
    }

    public function show(Reunion $reunion)
    {
        $reunion->load(['agendas', 'participants', 'compteRendu', 'creator']);
        return view('reunions.show', compact('reunion'));
    }

    public function edit(Reunion $reunion)
    {
        if (!Auth::user()->isResponsable()) {
            return abort(403);
        }
        $instances = \App\Models\Instance::all();
        $users = User::where('role', '!=', 'admin')->orderBy('name')->get();
        $currentParticipantIds = $reunion->participants->pluck('id')->toArray();
        return view('reunions.edit', compact('reunion', 'instances', 'users', 'currentParticipantIds'));
    }

    public function update(Request $request, Reunion $reunion)
    {
        if (!Auth::user()->isResponsable()) {
            return abort(403);
        }

        $validated = $request->validate([
            'titre' => 'required|string|max:200',
            'date' => 'required|date',
            'lieu' => 'nullable|string|max:200',
            'status' => 'required|in:planifiee,en_cours,terminee',
            'instance_id' => 'nullable|exists:instances,id',
            'type' => 'required|in:standard,elargie',
            'agenda' => 'nullable|array',
            'agenda.*.titre' => 'required|string|max:200',
            'agenda.*.description' => 'nullable|string',
        ]);

        $oldDate = $reunion->date->format('Y-m-d H:i');
        $oldLieu = $reunion->lieu;
        $oldAgendaCount = $reunion->agendas()->count();

        $reunion->update($validated);

        // Notify of changes
        $reasons = [];
        if ($oldDate != $reunion->date->format('Y-m-d H:i')) $reasons[] = "nouvel horaire : " . $reunion->date->format('d/m/Y H:i');
        if ($oldLieu != $reunion->lieu) $reasons[] = "nouveau lieu : " . ($reunion->lieu ?? 'Non spécifié');

        // Sync Agenda items
        if ($request->has('agenda')) {
            $reunion->agendas()->delete();
            foreach ($request->agenda as $index => $item) {
                $reunion->agendas()->create([
                    'titre' => $item['titre'],
                    'description' => $item['description'],
                    'ordre' => $index
                ]);
            }
            if (count($request->agenda) != $oldAgendaCount) $reasons[] = "l'ordre du jour a été mis à jour";
        }

        if (!empty($reasons)) {
            $message = "Mise à jour de la réunion \"{$reunion->titre}\" : " . implode(', ', $reasons);
            foreach ($reunion->participants as $participant) {
                $participant->notify(new \App\Notifications\MeetingNotification([
                    'title' => 'Réunion mise à jour',
                    'message' => $message,
                    'action_url' => route('reunions.show', $reunion),
                    'type' => 'update'
                ]));
            }
        }

        return redirect()->route('reunions.show', $reunion)->with('success', 'Réunion mise à jour.');
    }

    public function editDecisions(Reunion $reunion)
    {
        if (!Auth::user()->isResponsable()) {
            return abort(403);
        }
        $reunion->load('agendas');
        return view('reunions.decisions', compact('reunion'));
    }

    public function updateDecisions(Request $request, Reunion $reunion)
    {
        if (!Auth::user()->isResponsable()) {
            return abort(403);
        }

        $validated = $request->validate([
            'decisions' => 'required|array',
            'decisions.*' => 'nullable|string',
        ]);

        foreach ($validated['decisions'] as $agendaId => $decision) {
            \App\Models\Agenda::where('id', $agendaId)
                ->where('reunion_id', $reunion->id)
                ->update(['decision' => $decision]);
        }

        return redirect()->route('reunions.show', $reunion)->with('success', 'Décisions enregistrées avec succès.');
    }

    public function exportPV(Reunion $reunion)
    {
        $reunion->load(['agendas', 'participants', 'instance']);
        return view('reunions.pv', compact('reunion'));
    }

    public function destroy(Reunion $reunion)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isResponsable()) {
            return abort(403);
        }

        // Notify cancellation FIRST
        foreach ($reunion->participants as $participant) {
            $participant->notify(new \App\Notifications\MeetingNotification([
                'title' => 'Réunion annulée',
                'message' => "La réunion \"{$reunion->titre}\" prévue le {$reunion->date->format('d/m H:i')} a été annulée.",
                'action_url' => route('reunions.index'),
                'type' => 'cancellation'
            ]));
        }

        $reunion->delete();
        return redirect()->route('reunions.index')->with('success', 'Réunion supprimée et participants informés.');
    }
}
