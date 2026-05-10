@extends('layouts.app')

@section('title', 'Membres - ' . $instance->nom)
@section('page-title', $instance->nom)

@section('content')
<div class="animate-fade">
    <div class="card" style="margin-bottom: 2rem; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -20px; right: -20px; background: var(--accent); opacity: 0.1; width: 150px; height: 150px; border-radius: 50%;"></div>
        <div style="display: flex; align-items: center; gap: 1.5rem;">
            <div style="background: var(--accent); color: #fff; width: 60px; height: 60px; border-radius: 15px; display: flex; align-items: center; justify-content: center;">
                <i data-lucide="shield" style="width: 32px; height: 32px;"></i>
            </div>
            <div style="flex: 1;">
                <h2 style="font-size: 1.8rem; margin-bottom: 0.25rem;">{{ $instance->nom }}</h2>
                <p style="color: var(--text-muted);">{{ $instance->description ?? 'Liste officielle des membres de l\'instance.' }}</p>
            </div>
            @if(auth()->user()->isAdmin())
            <div style="display: flex; gap: 0.5rem;">
                <a href="{{ route('instances.edit', $instance) }}" class="btn btn-outline" style="padding: 0.6rem 1rem;">
                    <i data-lucide="edit-2"></i> Modifier l'instance
                </a>
            </div>
            @endif
        </div>
    </div>

    @if(auth()->user()->isAdmin())
    <div class="card" style="margin-bottom: 2rem; background: #f8fafc; border-style: dashed; border-width: 2px;">
        <h3 style="margin-bottom: 1.5rem; font-size: 1rem; display: flex; align-items: center; gap: 0.5rem;">
            <i data-lucide="user-plus" style="color: var(--accent);"></i> Ajouter un membre à cette commission
        </h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <!-- Register User -->
            <form action="{{ route('instances.members.add', $instance) }}" method="POST">
                @csrf
                <div style="flex: 1;">
                    <label style="font-size: 0.8rem; margin-bottom: 0.4rem; display: block; color: var(--text-muted);">Membre inscrit</label>
                    <select name="user_id" style="width: 100%;">
                        <option value="" disabled selected>Choisir un membre...</option>
                        @foreach($all_users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role }})</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" style="margin-top: 1rem; width: 100%;">
                    Ajouter l'inscrit
                </button>
            </form>

            <!-- Guest User -->
            <form action="{{ route('instances.members.add', $instance) }}" method="POST">
                @csrf
                <label style="font-size: 0.8rem; margin-bottom: 0.4rem; display: block; color: var(--text-muted);">Membre externe (non-inscrit)</label>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <input type="text" name="guest_name" placeholder="Nom complet" required style="font-size: 0.85rem;">
                    <input type="email" name="guest_email" placeholder="Email" required style="font-size: 0.85rem;">
                </div>
                <button type="submit" class="btn btn-outline" style="margin-top: 1rem; width: 100%; border-color: var(--accent); color: var(--accent);">
                    Ajouter l'externe
                </button>
            </form>
        </div>
    </div>
    @endif

    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1rem;">
            <h3 style="display: flex; align-items: center; gap: 0.75rem;">
                <i data-lucide="users" style="color: var(--accent);"></i> Membres de l'instance
                <span class="badge" style="background: var(--bg-main); color: var(--text-muted); font-size: 0.8rem; font-weight: normal; padding: 0.4rem 0.8rem; border-radius: 20px;" id="members-count">
                    {{ $instance->members->count() }} au total
                </span>
            </h3>
            
            <div style="position: relative; width: 300px;">
                <i data-lucide="search" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); width: 16px; color: var(--text-muted);"></i>
                <input type="text" id="member-search" placeholder="Filtrer les membres..." style="padding-left: 2.75rem; background: #f1f5f9; border-radius: 10px; font-size: 0.9rem;">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem;" id="members-grid">
            @forelse($instance->members as $member)
            @php 
                $name = $member->user_id ? $member->user->name : $member->guest_name;
                $email = $member->user_id ? $member->user->email : $member->guest_email;
                $isGuest = !$member->user_id;
            @endphp
            <div class="member-card animate-fade" data-name="{{ strtolower($name) }}" style="background: #f8fafc; padding: 1.5rem; border-radius: 16px; border: 1px solid var(--border-color); display: flex; align-items: flex-start; gap: 1.25rem; transition: all 0.3s; position: relative;">
                <div style="width: 52px; height: 52px; border-radius: 14px; background: #ffffff; border: 1px solid {{ $isGuest ? 'var(--text-muted)' : 'var(--accent)' }}; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; font-weight: 800; color: {{ $isGuest ? 'var(--text-muted)' : 'var(--accent)' }};">
                    {{ substr($name, 0, 1) }}
                </div>
                <div style="flex: 1;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <h4 style="font-size: 1.1rem; margin-bottom: 0.25rem; font-weight: 600;">{{ $name }}</h4>
                        @if(auth()->user()->isAdmin())
                        <form action="{{ route('instances.members.remove', [$instance, $member]) }}" method="POST" onsubmit="return confirm('Retirer ce membre de la commission ?')" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn" title="Retirer de l'instance" style="color: var(--danger);">
                                <i data-lucide="user-minus" style="width: 14px;"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                    <div style="display: inline-block; padding: 0.15rem 0.6rem; background: #f1f5f9; border-radius: 6px; font-size: 0.7rem; color: var(--text-muted); margin-bottom: 0.75rem; text-transform: uppercase;">
                        {{ $isGuest ? 'Externe' : ucfirst($member->user->role) }}
                    </div>
                    
                    <div style="display: flex; gap: 0.5rem; align-items: center;">
                        <a href="mailto:{{ $email }}" class="hover-accent" style="color: var(--text-muted); text-decoration: none; font-size: 0.85rem; display: flex; align-items: center; gap: 0.4rem;">
                            <i data-lucide="mail" style="width: 14px;"></i> {{ $email }}
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div id="no-results" style="grid-column: 1 / -1; text-align: center; color: var(--text-muted); padding: 4rem;">
                <i data-lucide="search-x" style="width: 48px; height: 48px; opacity: 0.2; margin-bottom: 1rem;"></i>
                <p>Aucun membre affecté à cette instance.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    document.getElementById('member-search').addEventListener('input', function() {
        const val = this.value.toLowerCase();
        const cards = document.querySelectorAll('.member-card');
        let count = 0;

        cards.forEach(card => {
            if (card.dataset.name.includes(val)) {
                card.style.display = 'flex';
                count++;
            } else {
                card.style.display = 'none';
            }
        });

        document.getElementById('members-count').textContent = count + ' affiché' + (count > 1 ? 's' : '');
    });
</script>

<style>
    .member-card:hover {
        transform: translateY(-5px);
        border-color: var(--accent) !important;
    }
    .hover-accent:hover {
        color: var(--accent) !important;
    }
</style>
@endsection
