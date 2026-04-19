@extends('layouts.app')

@section('title', 'Saisie des Décisions')
@section('page-title', 'Saisie des Résultats')

@section('content')
<div class="animate-fade" style="max-width: 900px; margin: 0 auto;">
    <div class="card" style="margin-bottom: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <h2 style="font-size: 1.5rem; margin-bottom: 0.5rem;">{{ $reunion->titre }}</h2>
                <div style="display: flex; gap: 1rem; color: var(--text-muted); font-size: 0.9rem;">
                    <span><i data-lucide="calendar" style="width: 14px;"></i> {{ $reunion->date }}</span>
                    <span><i data-lucide="map-pin" style="width: 14px;"></i> {{ $reunion->lieu }}</span>
                </div>
            </div>
            <span class="badge badge-{{ $reunion->status }}">
                {{ ucfirst(str_replace('_', ' ', $reunion->status)) }}
            </span>
        </div>
    </div>

    <form action="{{ route('reunions.decisions.update', $reunion) }}" method="POST">
        @csrf
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            @foreach($reunion->agendas as $agenda)
            <div class="card" style="border-left: 4px solid var(--accent); background: rgba(255,255,255,0.02);">
                <div style="margin-bottom: 1rem;">
                    <div style="font-weight: 700; color: var(--accent); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">
                        Point #{{ $loop->iteration }}
                    </div>
                    <h3 style="font-size: 1.2rem;">{{ $agenda->titre }}</h3>
                    @if($agenda->description)
                        <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 0.5rem;">{{ $agenda->description }}</p>
                    @endif
                </div>

                <div class="form-group">
                    <label for="decision_{{ $agenda->id }}">Décision / Résultat de la discussion</label>
                    <textarea 
                        id="decision_{{ $agenda->id }}" 
                        name="decisions[{{ $agenda->id }}]" 
                        rows="4" 
                        placeholder="Qu'est-ce qui a été décidé pour ce point ?..."
                        style="background: rgba(0,0,0,0.3); border-color: rgba(255,255,255,0.1);"
                    >{{ old('decisions.' . $agenda->id, $agenda->decision) }}</textarea>
                </div>
            </div>
            @endforeach
        </div>

        <div style="margin-top: 2rem; display: flex; justify-content: flex-end; gap: 1rem; padding-bottom: 4rem;">
            <a href="{{ route('reunions.show', $reunion) }}" class="btn btn-outline">Annuler</a>
            <button type="submit" class="btn btn-primary" style="padding: 1rem 2.5rem;">
                Enregistrer les résultats <i data-lucide="save"></i>
            </button>
        </div>
    </form>
</div>
@endsection
