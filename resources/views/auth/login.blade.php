@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
<div class="login-container">
    <div class="login-card animate-fade">
        <div style="text-align: center; margin-bottom: 2.5rem;">
            <div style="display: inline-flex; align-items: center; justify-content: center; width: 64px; height: 64px; background: rgba(79, 70, 229, 0.1); color: var(--accent); border-radius: 1rem; margin-bottom: 1.5rem;">
                <i data-lucide="graduation-cap" style="width: 32px; height: 32px;"></i>
            </div>
            <h2 style="font-size: 1.75rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.5rem; letter-spacing: -0.025em;">{{ config('app.name') }}</h2>
            <p style="color: var(--text-muted); font-size: 0.95rem;">Gestion des réunions et commissions</p>
        </div>

        <form action="{{ url('/login') }}" method="POST">
            @csrf
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="email">Adresse Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="votre@email.com">
                @error('email')
                    <span style="color: var(--danger); font-size: 0.8rem; margin-top: 0.5rem; display: block; font-weight: 500;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required placeholder="••••••••">
            </div>

            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem;">
                <label style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0; cursor: pointer; font-weight: 500; text-transform: none; letter-spacing: normal;">
                    <input type="checkbox" id="remember" name="remember" style="width: 16px; height: 16px; accent-color: var(--accent);">
                    Se souvenir de moi
                </label>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                Se connecter <i data-lucide="arrow-right" style="width: 18px;"></i>
            </button>
        </form>

        <div style="margin-top: 2.5rem; padding-top: 2rem; border-top: 1px solid #f1f5f9; text-align: center;">
            <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.75rem; text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em;">Identifiants de test</p>
            <div style="background: #f8fafc; padding: 1rem; border-radius: 0.75rem; display: inline-block; text-align: left;">
                <code style="display: block; font-size: 0.8rem; margin-bottom: 0.25rem;">admin@faculte.com / password</code>
                <code style="display: block; font-size: 0.8rem;">ahmed@faculte.com / password</code>
            </div>
        </div>
    </div>
</div>
@endsection
