@extends('layouts.app')

@section('title', 'Comptes-rendus')
@section('page-title', 'Comptes-rendus des réunions')

@section('content')
<div class="card animate-fade" style="padding: 0; overflow: hidden; border-radius: var(--radius-md);">
    <table style="border: none;">
        <thead>
            <tr style="background: #f8fafc;">
                <th style="border-left: none; border-top: none; padding: 1.25rem 1.5rem;">RÉUNION</th>
                <th style="border-top: none; padding: 1.25rem 1.5rem;">DATE RÉUNION</th>
                <th style="border-top: none; padding: 1.25rem 1.5rem;">FICHIER</th>
                <th style="border-top: none; padding: 1.25rem 1.5rem;">PUBLIÉ PAR</th>
                <th style="border-right: none; border-top: none; padding: 1.25rem 1.5rem; text-align: right;">ACTIONS</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reports as $report)
            <tr class="table-row-hover">
                <td style="padding: 1.25rem 1.5rem; border-left: none; font-weight: 700; color: var(--text-main);">
                    {{ $report->reunion->titre }}
                </td>
                <td style="padding: 1.25rem 1.5rem;">
                    {{ \Carbon\Carbon::parse($report->reunion->date)->format('d/m/Y') }}
                </td>
                <td style="padding: 1.25rem 1.5rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--accent); font-weight: 600;">
                        <i data-lucide="file-text" style="width: 18px;"></i>
                        <span style="font-size: 0.85rem;">{{ $report->file_name }}</span>
                    </div>
                </td>
                <td style="padding: 1.25rem 1.5rem;">
                    <span style="font-size: 0.85rem; color: var(--text-muted);">{{ $report->uploader->name }}</span>
                </td>
                <td style="padding: 1.25rem 1.5rem; border-right: none; text-align: right;">
                    <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                        <a href="{{ route('reports.download', $report) }}" class="action-btn" title="Télécharger">
                            <i data-lucide="download" style="width: 16px;"></i>
                        </a>
                        <a href="{{ route('reunions.show', $report->reunion) }}" class="action-btn" title="Voir la réunion">
                            <i data-lucide="external-link" style="width: 16px;"></i>
                        </a>
                        @if(auth()->user()->isAdmin())
                        <form action="{{ route('reports.destroy', $report) }}" method="POST" onsubmit="return confirm('Supprimer ce compte-rendu ?');" style="display: inline;">
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
            @empty
            <tr>
                <td colspan="5" style="padding: 3rem; text-align: center; color: var(--text-muted);">
                    <i data-lucide="file-x" style="width: 48px; opacity: 0.1; margin-bottom: 1rem;"></i>
                    <p>Aucun compte-rendu disponible pour le moment.</p>
                </td>
            </tr>
            @endforelse
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
