@extends('layouts.app')

@section('title', 'Gestion des Instances')
@section('page-title', 'Commissions & Instances')

@section('content')
<div class="animate-fade">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <p style="color: var(--text-muted);">{{ $instances->count() }} commissions configurées</p>
        <a href="{{ route('instances.create') }}" class="btn btn-primary">
            <i data-lucide="plus-circle"></i> Nouvelle Commission
        </a>
    </div>

    <div class="card">
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 1.5rem;">
            @foreach($instances as $instance)
            <div class="card" style="margin-bottom: 0; display: flex; flex-direction: column; gap: 1rem; transition: transform 0.2s;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="background: var(--bg-main); color: var(--accent); width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <i data-lucide="shield"></i>
                        </div>
                        <div>
                            <h3 style="font-size: 1.1rem; font-weight: 700;">{{ $instance->nom }}</h3>
                            <span style="font-size: 0.8rem; color: var(--text-muted);">{{ $instance->members_count }} membres</span>
                        </div>
                    </div>
                    <div style="display: flex; gap: 0.5rem;">
                        <a href="{{ route('instances.edit', $instance) }}" class="action-btn" title="Modifier">
                            <i data-lucide="edit-2" style="width: 14px;"></i>
                        </a>
                        <form action="{{ route('instances.destroy', $instance) }}" method="POST" onsubmit="return confirm('Supprimer cette commission ? Cela ne supprimera pas les membres.')" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn" style="color: var(--danger);" title="Supprimer">
                                <i data-lucide="trash-2" style="width: 14px;"></i>
                            </button>
                        </form>
                    </div>
                </div>
                
                <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.5; flex: 1;">
                    {{ Str::limit($instance->description, 100) ?? 'Aucune description fournie.' }}
                </p>

                <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
                    <a href="{{ route('instances.show', $instance) }}" class="btn btn-outline" style="width: 100%; justify-content: center;">
                        <i data-lucide="users"></i> Gérer les membres
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
