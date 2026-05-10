@extends('layouts.app')

@section('title', 'Modifier ' . $instance->nom)
@section('page-title', 'Modifier la Commission')

@section('content')
<div class="animate-fade" style="max-width: 800px; margin: 0 auto;">
    <div class="card">
        <form action="{{ route('instances.update', $instance) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="nom">Nom de la Commission</label>
                <input type="text" name="nom" id="nom" value="{{ old('nom', $instance->nom) }}" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" rows="5">{{ old('description', $instance->description) }}</textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--border-color);">
                <a href="{{ route('instances.index') }}" class="btn btn-outline">Annuler</a>
                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
            </div>
        </form>
    </div>
</div>
@endsection
