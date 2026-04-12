@extends('layouts.app')

@section('title', 'Tableau de Bord')
@section('page-title', 'Tableau de Bord')

@section('content')
<div class="stats-grid animate-fade">
    @if(auth()->user()->isAdmin())
        <div class="stat-card">
            <div class="stat-value">{{ $stats['total_reunions'] }}</div>
            <div class="stat-label"><i data-lucide="calendar"></i> Total Réunions</div>
        </div>
        <div class="stat-card">
            <div class="stat-value" style="color: var(--accent);">{{ $stats['pending_reunions'] }}</div>
            <div class="stat-label">Réunions planifiées</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $stats['total_users'] }}</div>
            <div class="stat-label"><i data-lucide="users"></i> Participants</div>
        </div>
    @else
        <div class="stat-card">
            <div class="stat-value">{{ $stats['my_reunions'] }}</div>
            <div class="stat-label"><i data-lucide="calendar"></i> Mes Réunions</div>
        </div>
        <div class="stat-card">
            <div class="stat-value" style="color: var(--accent);">{{ $stats['pending_invitations'] }}</div>
            <div class="stat-label">Invitations en attente</div>
        </div>
    @endif
</div>

<div class="card animate-fade" style="padding: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2 style="font-size: 1.25rem;">Activités Récentes</h2>
        <a href="{{ route('reunions.index') }}" class="btn btn-outline">Voir tout</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>TITRE</th>
                <th>DATE</th>
                <th>LIEU</th>
                <th>STATUT</th>
                <th>ACTION</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recent_reunions as $reunion)
            <tr>
                <td style="font-weight: 600;">{{ $reunion->titre }}</td>
                <td>{{ \Carbon\Carbon::parse($reunion->date)->format('d M, Y') }}</td>
                <td>{{ $reunion->lieu ?? 'Non spécifié' }}</td>
                <td>
                    <span class="badge badge-{{ $reunion->status }}">
                        {{ ucfirst(str_replace('_', ' ', $reunion->status)) }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('reunions.show', $reunion) }}" class="btn btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">
                        <i data-lucide="eye"></i> Ouvrir
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 3rem;">
                    <i data-lucide="info" style="display: block; margin: 0 auto 1rem; opacity: 0.5;"></i>
                    Aucune réunion trouvée.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if(auth()->user()->isAdmin())
<div style="margin-top: 3rem;" class="animate-fade">
    <div class="card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1), transparent); border-color: rgba(212, 175, 55, 0.2);">
        <div style="display: flex; gap: 2rem; align-items: center;">
            <div style="background: var(--accent); color: var(--bg-dark); width: 60px; height: 60px; border-radius: 15px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                <i data-lucide="plus"></i>
            </div>
            <div>
                <h3 style="margin-bottom: 0.5rem;">Prêt à organiser une nouvelle réunion ?</h3>
                <p style="color: var(--text-muted); margin-bottom: 1rem;">Définissez l'agenda, invitez les professeurs et préparez les discussions.</p>
                <a href="{{ route('reunions.create') }}" class="btn btn-primary">Créer une réunion</a>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
