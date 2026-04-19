@extends('layouts.app')

@section('title', 'Modifier le Participant')
@section('page-title', 'Modifier: ' . $participant->name)

@section('content')
<div class="card animate-fade" style="max-width: 600px; margin: 0 auto;">
    <form action="{{ route('participants.update', $participant) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="name">Nom complet</label>
            <input type="text" id="name" name="name" required placeholder="Ex: Mohammed El Amrani" value="{{ old('name', $participant->name) }}">
        </div>

        <div class="form-group">
            <label for="email">Adresse Email (@ucd.ac.ma)</label>
            <input type="email" id="email" name="email" required placeholder="nom.prenom@ucd.ac.ma" value="{{ old('email', $participant->email) }}">
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
            <label for="password">Nouveau mot de passe (Laisser vide pour ne pas changer)</label>
            <div style="position: relative;">
                <input type="password" id="password" name="password" placeholder="Changer le mot de passe" style="padding-right: 3rem;">
                <button type="button" onclick="togglePassword('password')" style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 0.5rem; display: flex; align-items: center;">
                    <i data-lucide="eye" id="password-eye" style="width: 18px;"></i>
                </button>
            </div>
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

        <div style="margin-top: 2rem; background: rgba(255,255,255,0.02); padding: 1.5rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
            <h3 style="font-size: 1rem; margin-bottom: 1rem; color: var(--accent); display: flex; align-items: center; gap: 0.5rem;">
                <i data-lucide="shield"></i> Commissions / Instances
            </h3>
            <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 1.5rem;">Sélectionnez les instances auxquelles ce membre appartient.</p>
            
            <div style="display: grid; grid-template-columns: 1fr; gap: 0.75rem;">
                @foreach($instances as $instance)
                @php $isSelected = $participant->instances->contains($instance->id); @endphp
                <label style="display: flex; align-items: center; gap: 1rem; padding: 0.75rem; background: rgba(0,0,0,0.2); border-radius: 8px; cursor: pointer; border: 1px solid {{ $isSelected ? 'rgba(212, 175, 55, 0.4)' : 'rgba(255,255,255,0.05)' }};">
                    <input type="checkbox" name="instances[]" value="{{ $instance->id }}" {{ $isSelected ? 'checked' : '' }}>
                    <span style="font-size: 0.9rem;">{{ $instance->nom }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: flex-end; gap: 1rem;">
            <a href="{{ route('participants.index') }}" class="btn btn-outline">Annuler</a>
            <button type="submit" class="btn btn-primary">
                Enregistrer les modifications <i data-lucide="check"></i>
            </button>
        </div>
    </form>
</div>
@endsection
