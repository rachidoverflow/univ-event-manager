@extends('layouts.app')

@section('title', 'Nouvelle Réunion')
@section('page-title', 'Créer une Réunion')

@section('content')
<div class="card animate-fade" style="max-width: 800px; margin: 0 auto;">
    <form action="{{ route('reunions.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="titre">Titre de la réunion</label>
            <input type="text" id="titre" name="titre" required placeholder="Ex: Bureau décanat, Commission recherche..." value="{{ old('titre') }}">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label for="date">Date tenue</label>
                <input type="date" id="date" name="date" required value="{{ old('date') }}">
            </div>
            <div class="form-group">
                <label for="lieu">Lieu / Salle</label>
                <input type="text" id="lieu" name="lieu" placeholder="Ex: Salle des actes, Amphi A..." value="{{ old('lieu') }}">
            </div>
        </div>

        <div style="margin-top: 2rem; display: flex; justify-content: flex-end; gap: 1rem; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 2rem;">
            <a href="{{ route('reunions.index') }}" class="btn btn-outline">Annuler</a>
            <button type="submit" class="btn btn-primary" style="padding: 0.8rem 2rem;">
                Créer la réunion <i data-lucide="check"></i>
            </button>
        </div>
    </form>
</div>
@endsection
