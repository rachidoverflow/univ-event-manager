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

<div class="card animate-fade" style="padding: 0; overflow: hidden; border-radius: var(--radius-md);">
    <table style="border: none;">
        <thead>
            <tr style="background: #f8fafc;">
                <th style="border-left: none; border-top: none; padding: 1.25rem 1.5rem;">TITRE</th>
                <th style="border-top: none; padding: 1.25rem 1.5rem;">DATE</th>
                <th style="border-top: none; padding: 1.25rem 1.5rem;">LIEU</th>
                <th style="border-top: none; padding: 1.25rem 1.5rem;">STATUT</th>
                <th style="border-top: none; padding: 1.25rem 1.5rem;">ORGANISATEUR</th>
                <th style="border-right: none; border-top: none; padding: 1.25rem 1.5rem; text-align: right;">ACTIONS</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reunions as $reunion)
            <tr class="table-row-hover">
                <td style="padding: 1.25rem 1.5rem; border-left: none; font-weight: 700; color: var(--text-main);">
                    {{ $reunion->titre }}
                </td>
                <td style="padding: 1.25rem 1.5rem; font-weight: 600;">
                    {{ \Carbon\Carbon::parse($reunion->date)->format('d/m/Y') }}
                </td>
                <td style="padding: 1.25rem 1.5rem; color: var(--text-muted);">
                    {{ $reunion->lieu ?? 'N/A' }}
                </td>
                <td style="padding: 1.25rem 1.5rem;">
                    <span class="badge badge-{{ $reunion->status }}" style="padding: 4px 10px; border-radius: 30px;">
                        {{ ucfirst(str_replace('_', ' ', $reunion->status)) }}
                    </span>
                </td>
                <td style="padding: 1.25rem 1.5rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <div style="width: 28px; height: 28px; border-radius: 50%; background: var(--accent); color: white; font-size: 0.7rem; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                            {{ substr($reunion->creator->name, 0, 1) }}
                        </div>
                        <span style="font-size: 0.85rem; font-weight: 500;">{{ $reunion->creator->name }}</span>
                    </div>
                </td>
                <td style="padding: 1.25rem 1.5rem; border-right: none; text-align: right;">
                    <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                        <a href="{{ route('reunions.show', $reunion) }}" class="action-btn" title="Voir">
                            <i data-lucide="eye" style="width: 16px;"></i>
                        </a>
                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('reunions.edit', $reunion) }}" class="action-btn" style="color: var(--accent); border-color: rgba(79, 70, 229, 0.2);" title="Modifier">
                            <i data-lucide="edit-3" style="width: 16px;"></i>
                        </a>
                        <form action="{{ route('reunions.destroy', $reunion) }}" method="POST" onsubmit="return confirm('Supprimer cette réunion ?');" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn" style="color: var(--danger); border-color: rgba(220, 38, 38, 0.2);" title="Supprimer">
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

<style>
    .table-row-hover:hover td {
        background-color: #fcfdfe !important;
    }
    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: white;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        transition: all 0.2s;
        text-decoration: none;
    }
    .action-btn:hover {
        background: #f8fafc;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
</style>
@endsection
