@extends('layouts.app')

@section('title', 'Créer une Commission')
@section('page-title', 'Nouvelle Commission')

@section('content')
<div class="animate-fade" style="max-width: 800px; margin: 0 auto;">
    <div class="card">
        <form action="{{ route('instances.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="nom">Nom de la Commission</label>
                <input type="text" name="nom" id="nom" placeholder="Ex: Conseil de Faculté" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" rows="5" placeholder="Objectifs et responsabilités de cette commission..."></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--border-color);">
                <a href="{{ route('instances.index') }}" class="btn btn-outline">Annuler</a>
                <button type="submit" class="btn btn-primary">Créer la commission</button>
            </div>
        </form>
    </div>
</div>
@endsection
