@extends('layouts.app')

@section('title', 'Modifier ' . $reunion->titre)
@section('page-title', 'Modifier la Réunion')

@section('content')
<div class="card animate-fade" style="max-width: 800px; margin: 0 auto;">
    <form action="{{ route('reunions.update', $reunion) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="titre">Titre de la réunion</label>
            <input type="text" id="titre" name="titre" required value="{{ old('titre', $reunion->titre) }}">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label for="date">Date tenue</label>
                <input type="date" id="date" name="date" required value="{{ old('date', $reunion->date) }}">
            </div>
            <div class="form-group">
                <label for="lieu">Lieu / Salle</label>
                <input type="text" id="lieu" name="lieu" value="{{ old('lieu', $reunion->lieu) }}">
            </div>
        </div>

        <div class="form-group">
            <label for="status">Statut de la réunion</label>
            <select name="status" id="status" required>
                <option value="planifiee" {{ $reunion->status == 'planifiee' ? 'selected' : '' }}>Planifiée</option>
                <option value="en_cours" {{ $reunion->status == 'en_cours' ? 'selected' : '' }}>En cours</option>
                <option value="terminee" {{ $reunion->status == 'terminee' ? 'selected' : '' }}>Terminée</option>
            </select>
        </div>

        <div style="margin-top: 2rem; display: flex; justify-content: flex-end; gap: 1rem; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 2rem;">
            <a href="{{ route('reunions.show', $reunion) }}" class="btn btn-outline">Annuler</a>
            <button type="submit" class="btn btn-primary" style="padding: 0.8rem 2rem;">
                Mettre à jour <i data-lucide="save"></i>
            </button>
        </div>
    </form>
</div>
@endsection
