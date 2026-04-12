@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
<div class="login-container">
    <div class="login-card animate-fade">
        <div style="text-align: center; margin-bottom: 2rem;">
            <div style="color: var(--accent); font-size: 3rem; margin-bottom: 1rem;">
                <i data-lucide="graduation-cap"></i>
            </div>
            <h2 style="font-size: 1.5rem; margin-bottom: 0.5rem;">Univ-Event Manager</h2>
            <p style="color: var(--text-muted);">Accédez à votre espace faculty</p>
        </div>

        <form action="{{ url('/login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">Adresse Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nom@faculte.com">
                @error('email')
                    <span style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required placeholder="••••••••">
            </div>

            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 2rem;">
                <input type="checkbox" id="remember" name="remember" style="width: auto;">
                <label for="remember" style="margin-bottom: 0;">Se souvenir de moi</label>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 1rem;">
                Se connecter <i data-lucide="arrow-right"></i>
            </button>
        </form>

        <div style="margin-top: 2rem; text-align: center; font-size: 0.875rem; color: var(--text-muted);">
            <p>Identifiants de démonstration :<br>
            <strong>admin@faculte.com</strong> / password<br>
            <strong>ahmed@faculte.com</strong> / password</p>
        </div>
    </div>
</div>
@endsection
