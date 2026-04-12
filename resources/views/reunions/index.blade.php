@extends('layouts.app')

@section('title', 'Toutes les Réunions')
@section('page-title', 'Gestion des Réunions')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <p style="color: var(--text-muted);">{{ $reunions->count() }} réunions enregistrées</p>
    @if(auth()->user()->isAdmin())
    <a href="{{ route('reunions.create') }}" class="btn btn-primary">
        <i data-lucide="plus-circle"></i> Nouvelle Réunion
    </a>
    @endif
</div>

<div class="card animate-fade">
    <div style="margin-bottom: 2rem;">
        <input type="text" placeholder="Rechercher une réunion..." style="max-width: 400px; background: rgba(255,255,255,0.05);">
    </div>

    <table>
        <thead>
            <tr>
                <th>TITRE</th>
                <th>DATE</th>
                <th>LIEU</th>
                <th>STATUT</th>
                <th>ORGANISATEUR</th>
                <th style="text-align: right;">ACTIONS</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reunions as $reunion)
            <tr>
                <td style="font-weight: 600;">{{ $reunion->titre }}</td>
                <td>{{ \Carbon\Carbon::parse($reunion->date)->format('d/m/Y') }}</td>
                <td>{{ $reunion->lieu ?? 'N/A' }}</td>
                <td>
                    <span class="badge badge-{{ $reunion->status }}">
                        {{ str_replace('_', ' ', $reunion->status) }}
                    </span>
                </td>
                <td>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <div style="width: 24px; height: 24px; border-radius: 50%; background: var(--primary-light); font-size: 0.6rem; display: flex; align-items: center; justify-content: center;">
                            {{ substr($reunion->creator->name, 0, 1) }}
                        </div>
                        <span style="font-size: 0.9rem;">{{ $reunion->creator->name }}</span>
                    </div>
                </td>
                <td style="text-align: right;">
                    <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                        <a href="{{ route('reunions.show', $reunion) }}" class="btn btn-outline" style="padding: 0.4rem; border-radius: 8px;">
                            <i data-lucide="eye" style="width: 16px;"></i>
                        </a>
                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('reunions.edit', $reunion) }}" class="btn btn-outline" style="padding: 0.4rem; border-radius: 8px; border-color: #60a5fa; color: #60a5fa;">
                            <i data-lucide="edit-3" style="width: 16px;"></i>
                        </a>
                        <form action="{{ route('reunions.destroy', $reunion) }}" method="POST" onsubmit="return confirm('Supprimer cette réunion ?');" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline" style="padding: 0.4rem; border-radius: 8px; border-color: var(--danger); color: var(--danger);">
                                <i data-lucide="trash-2" style="width: 16px;"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
