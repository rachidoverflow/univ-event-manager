@extends('layouts.app')

@section('title', 'Gestion des Participants')
@section('page-title', 'Annuaire des Participants')

@section('content')
<div class="animate-fade">
    <div class="card" style="margin-bottom: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h2 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.25rem;">Annuaire des Participants</h2>
                <p style="color: var(--text-muted); font-size: 0.9rem;">Liste officielle des enseignants et du personnel administratif.</p>
            </div>
            
            <div style="display: flex; align-items: center; gap: 1rem; flex: 1; max-width: 500px;">
                <div style="position: relative; flex: 1;">
                    <i data-lucide="search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 16px; color: var(--text-muted);"></i>
                    <input type="text" id="participant-search" placeholder="Rechercher par nom, email ou rôle..." style="padding-left: 40px; background: #f8fafc; border-radius: 30px; height: 42px;">
                </div>
                @if(auth()->user()->isAdmin())
                <a href="{{ route('participants.create') }}" class="btn btn-primary" style="height: 42px; white-space: nowrap;">
                    <i data-lucide="user-plus"></i> Ajouter
                </a>
                @endif
            </div>
        </div>
    </div>

    <div class="card">
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem;">
            @foreach($participants as $participant)
            <div style="background: #f8fafc; padding: 1.5rem; border-radius: 16px; border: 1px solid var(--border-color); display: flex; align-items: flex-start; gap: 1.25rem; position: relative;" class="participant-card">
                <div style="width: 56px; height: 56px; border-radius: 14px; background: #ffffff; border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: center; font-size: 1.4rem; font-weight: 800; color: var(--accent);">
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
                    <div style="display: inline-block; padding: 0.15rem 0.6rem; background: #f1f5f9; border-radius: 6px; font-size: 0.7rem; color: var(--text-muted); margin-bottom: 0.75rem; text-transform: uppercase;">
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

<script>
    document.getElementById('participant-search').addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase();
        const cards = document.querySelectorAll('.participant-card');
        
        cards.forEach(card => {
            const text = card.textContent.toLowerCase();
            if (text.includes(query)) {
                card.style.display = 'flex';
                card.classList.add('animate-fade');
            } else {
                card.style.display = 'none';
            }
        });
    });
</script>
@endsection
