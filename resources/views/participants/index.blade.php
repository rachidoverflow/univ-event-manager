@extends('layouts.app')

@section('title', 'Gestion des Participants')
@section('page-title', 'Annuaire des Participants')

@section('content')
<div class="animate-fade">
    <div class="card" style="margin-bottom: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.25rem;">Annuaire des Participants</h2>
                <p style="color: var(--text-muted); font-size: 0.9rem;">Liste officielle des enseignants et du personnel administratif.</p>
            </div>
            @if(auth()->user()->isAdmin())
            <a href="{{ route('participants.create') }}" class="btn btn-primary">
                <i data-lucide="user-plus"></i> Ajouter un membre
            </a>
            @endif
        </div>
    </div>

    <div class="card">
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem;">
            @foreach($participants as $participant)
            <div style="background: rgba(255,255,255,0.02); padding: 1.5rem; border-radius: 16px; border: 1px solid rgba(255,255,255,0.05); display: flex; align-items: flex-start; gap: 1.25rem; position: relative;" class="participant-card">
                <div style="width: 56px; height: 56px; border-radius: 14px; background: var(--bg-dark); border: 1px solid rgba(212, 175, 55, 0.3); display: flex; align-items: center; justify-content: center; font-size: 1.4rem; font-weight: 800; color: var(--accent);">
                    {{ substr($participant->name, 0, 1) }}
                </div>
                <div style="flex: 1;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <h3 style="font-size: 1.15rem; margin-bottom: 0.25rem;">{{ $participant->name }}</h3>
                        @if(auth()->user()->isAdmin())
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('participants.edit', $participant) }}" class="action-btn" title="Modifier">
                                <i data-lucide="edit-2" style="width: 14px;"></i>
                            </a>
                            <form action="{{ route('participants.destroy', $participant) }}" method="POST" onsubmit="return confirm('Supprimer ce participant ?')" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn text-danger" title="Supprimer">
                                    <i data-lucide="trash-2" style="width: 14px;"></i>
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                    <div style="display: inline-block; padding: 0.15rem 0.6rem; background: rgba(255,255,255,0.05); border-radius: 6px; font-size: 0.7rem; color: var(--text-muted); margin-bottom: 0.75rem; text-transform: uppercase;">
                        {{ ucfirst($participant->role) }}
                    </div>
                    
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <a href="mailto:{{ $participant->email }}" style="display: flex; align-items: center; gap: 0.5rem; color: var(--text-muted); text-decoration: none; font-size: 0.85rem;" class="hover-accent">
                            <i data-lucide="mail" style="width: 14px;"></i> {{ $participant->email }}
                        </a>
                        
                        @if($participant->instances->count() > 0)
                        <div style="display: flex; align-items: flex-start; gap: 0.5rem; color: var(--text-muted); font-size: 0.85rem; margin-top: 0.5rem;">
                            <i data-lucide="shield-check" style="width: 14px; margin-top: 3px;"></i>
                            <div style="display: flex; flex-wrap: wrap; gap: 0.25rem;">
                                @foreach($participant->instances as $inst)
                                <span style="font-size: 0.75rem; color: var(--accent); opacity: 0.8;">{{ $inst->nom }}{{ !$loop->last ? ',' : '' }}</span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<style>
    .hover-accent:hover {
        color: var(--accent) !important;
    }
</style>
@endsection
