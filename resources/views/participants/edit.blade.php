@extends('layouts.app')

@section('title', 'Modifier le Participant')
@section('page-title', 'Modifier le Profil')

@section('content')
<div class="animate-fade" style="max-width: 800px; margin: 0 auto;">
    <form action="{{ route('participants.update', $participant) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <!-- Informations de base -->
            <div class="card" style="margin-bottom: 0;">
                <h3 style="font-size: 1rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem; color: var(--primary);">
                    <i data-lucide="user"></i> Informations personnelles
                </h3>
                
                <div class="form-group">
                    <label for="name">Nom complet</label>
                    <input type="text" id="name" name="name" required value="{{ old('name', $participant->name) }}">
                </div>

                <div class="form-group">
                    <label for="email">Adresse Email</label>
                    <input type="email" id="email" name="email" required value="{{ old('email', $participant->email) }}">
                </div>

                <div class="form-group">
                    <label for="role">Rôle / Fonction</label>
                    <select id="role" name="role" required>
                        <option value="enseignant" {{ old('role', $participant->role) == 'enseignant' ? 'selected' : '' }}>Enseignant</option>
                        <option value="fonctionnaire" {{ old('role', $participant->role) == 'fonctionnaire' ? 'selected' : '' }}>Fonctionnaire</option>
                        <option value="responsable" {{ old('role', $participant->role) == 'responsable' ? 'selected' : '' }}>Responsable de Commission</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="password">Nouveau mot de passe (laisser vide pour ne pas changer)</label>
                    <div style="position: relative;">
                        <input type="password" id="password" name="password" placeholder="Changer le mot de passe" style="padding-right: 3rem;">
                        <button type="button" onclick="togglePassword('password')" style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 0.5rem; display: flex; align-items: center;">
                            <i data-lucide="eye" id="password-eye" style="width: 18px;"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Commissions -->
            <div class="card" style="margin-bottom: 0;">
                <h3 style="font-size: 1rem; margin-bottom: 1rem; color: var(--accent); display: flex; align-items: center; gap: 0.5rem;">
                    <i data-lucide="shield"></i> Commissions d'appartenance
                </h3>
                <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 1.5rem;">Cochez les instances dont ce membre fait partie.</p>
                
                <div style="display: flex; flex-direction: column; gap: 0.5rem; max-height: 350px; overflow-y: auto; padding-right: 0.5rem;">
                    @php 
                        $currentInstanceIds = $participant->instances->pluck('id')->toArray();
                    @endphp
                    @foreach($instances as $instance)
                    <label class="commission-checkbox">
                        <input type="checkbox" name="instances[]" value="{{ $instance->id }}" {{ in_array($instance->id, old('instances', $currentInstanceIds)) ? 'checked' : '' }}>
                        <div class="checkbox-card">
                            <span style="font-size: 0.9rem; font-weight: 500;">{{ $instance->nom }}</span>
                            <i data-lucide="check-circle-2" class="check-icon"></i>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>

        <div style="margin-top: 2rem; padding: 1.5rem; background: #fff; border-radius: var(--radius-md); border: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 1rem;">
            <a href="{{ route('participants.index') }}" class="btn btn-outline">Annuler</a>
            <button type="submit" class="btn btn-primary" style="padding-left: 2rem; padding-right: 2rem;">
                Mettre à jour le profil <i data-lucide="save"></i>
            </button>
        </div>
    </form>
</div>

<script>
    function togglePassword(id) {
        const input = document.getElementById(id);
        const eye = document.getElementById(id + '-eye');
        if (input.type === 'password') {
            input.type = 'text';
            eye.setAttribute('data-lucide', 'eye-off');
        } else {
            input.type = 'password';
            eye.setAttribute('data-lucide', 'eye');
        }
        lucide.createIcons();
    }
</script>

<style>
    .commission-checkbox {
        cursor: pointer;
        display: block;
    }
    .commission-checkbox input {
        display: none;
    }
    .checkbox-card {
        padding: 0.85rem 1.25rem;
        background: #f8fafc;
        border: 1px solid var(--border-color);
        border-radius: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.2s;
    }
    .commission-checkbox input:checked + .checkbox-card {
        background: rgba(79, 70, 229, 0.05);
        border-color: var(--accent);
        color: var(--accent);
    }
    .check-icon {
        width: 18px;
        opacity: 0;
        transition: opacity 0.2s;
    }
    .commission-checkbox input:checked + .checkbox-card .check-icon {
        opacity: 1;
    }
    .checkbox-card:hover {
        border-color: var(--accent);
    }
</style>
@endsection
