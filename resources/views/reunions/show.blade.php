@extends('layouts.app')

@section('title', $reunion->titre)
@section('page-title', 'Détails de la Réunion')

@section('content')
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;" class="animate-fade">
    <!-- Left Column: Details & Agenda -->
    <div style="display: flex; flex-direction: column; gap: 2rem;">
        <!-- Header Card -->
        <div class="card" style="position: relative; overflow: hidden;">
            <div style="position: absolute; top: -20px; right: -20px; background: var(--accent); opacity: 0.1; width: 150px; height: 150px; border-radius: 50%;"></div>
            
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem;">
                <div>
                    <h2 style="font-size: 2rem; margin-bottom: 0.5rem;">{{ $reunion->titre }}</h2>
                    <p style="color: var(--text-muted); display: flex; align-items: center; gap: 0.5rem;">
                        <i data-lucide="map-pin" style="width: 16px;"></i> {{ $reunion->lieu ?? 'Lieu non défini' }}
                        <span style="margin: 0 1rem; color: rgba(255,255,255,0.1);">|</span>
                        <i data-lucide="calendar" style="width: 16px;"></i> {{ \Carbon\Carbon::parse($reunion->date)->format('d F Y') }}
                    </p>
                </div>
                <span class="badge badge-{{ $reunion->status }}" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
                    {{ str_replace('_', ' ', $reunion->status) }}
                </span>
            </div>

            <div style="display: flex; gap: 0.8rem; margin-bottom: 1.5rem; justify-content: flex-end;">
                @if(auth()->user()->role === 'responsable')
                    <a href="{{ route('reunions.decisions.edit', $reunion) }}" class="btn btn-outline" style="border-color: var(--success); color: var(--success);">
                        <i data-lucide="check-square"></i> Saisir les décisions
                    </a>
                    <a href="{{ route('reunions.pv.export', $reunion) }}" class="btn btn-primary" target="_blank">
                        <i data-lucide="printer"></i> Générer le PV
                    </a>
                @endif

                @if(auth()->user()->isResponsable())
                    <a href="{{ route('reunions.edit', $reunion) }}" class="btn btn-outline">
                        <i data-lucide="edit-3"></i> Modifier
                    </a>
                @endif
            </div>

            @if(!auth()->user()->isAdmin())
            <div style="border-top: 1px solid rgba(255,255,255,0.05); padding-top: 1.5rem; display: flex; align-items: center; justify-content: space-between;">
                <p>Votre statut : <strong>
                    @php 
                        $pivot = $reunion->participants->find(auth()->id())->pivot ?? null;
                    @endphp
                    {{ $pivot ? ucfirst($pivot->response_status) : 'Non invité' }}
                </strong></p>
                <div style="display: flex; gap: 1rem;">
                    <form action="{{ route('participants.status', $reunion) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="accepted">
                        <button type="submit" class="btn btn-primary" style="background: var(--success); color: white;">Accepter</button>
                    </form>
                    <form action="{{ route('participants.status', $reunion) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="declined">
                        <button type="submit" class="btn btn-outline" style="border-color: var(--danger); color: var(--danger);">Refuser</button>
                    </form>
                </div>
            </div>
            @endif
        </div>

        <!-- Agenda Section -->
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h3 style="display: flex; align-items: center; gap: 0.75rem;">
                    <i data-lucide="list-checks" style="color: var(--accent);"></i> Ordre du jour
                </h3>
                @if(auth()->user()->isAdmin())
                <button type="button" onclick="document.getElementById('agenda-form').style.display='block'" class="btn btn-outline" style="font-size: 0.8rem;">
                    <i data-lucide="plus"></i> Ajouter un point
                </button>
                @endif
            </div>

            @if(auth()->user()->isAdmin())
            <div id="agenda-form" style="display: none; background: rgba(0,0,0,0.2); padding: 1.5rem; border-radius: 12px; margin-bottom: 2rem;">
                <form action="{{ route('agenda.store', $reunion) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <input type="text" name="titre" placeholder="Titre du point" required>
                    </div>
                    <div class="form-group">
                        <textarea name="description" placeholder="Description détaillée (optionnel)" rows="2"></textarea>
                    </div>
                    <div style="display: flex; justify-content: flex-end; gap: 1rem;">
                        <button type="button" onclick="this.parentElement.parentElement.parentElement.style.display='none'" class="btn btn-outline">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
            @endif

            <div style="display: flex; flex-direction: column; gap: 1rem;">
                @forelse($reunion->agendas as $index => $item)
                <div style="background: rgba(255,255,255,0.02); padding: 1.25rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05); display: flex; gap: 1rem;">
                    <div style="color: var(--accent); font-weight: 800; font-size: 1.2rem;">{{ $index + 1 }}.</div>
                    <div style="flex: 1;">
                        <h4 style="margin-bottom: 0.25rem;">{{ $item->titre }}</h4>
                        <p style="color: var(--text-muted); font-size: 0.9rem;">{{ $item->description }}</p>
                        @if($item->decision)
                        <div style="margin-top: 1rem; padding: 1rem; background: rgba(16, 185, 129, 0.05); border-radius: 8px; border-left: 3px solid var(--success);">
                            <div style="font-size: 0.75rem; font-weight: 700; color: var(--success); text-transform: uppercase; margin-bottom: 0.25rem;">Décision</div>
                            <p style="font-size: 0.9rem; font-style: italic;">{{ $item->decision }}</p>
                        </div>
                        @endif
                    </div>
                    @if(auth()->user()->isAdmin())
                    <form action="{{ route('agenda.destroy', $item) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background: none; border: none; color: var(--danger); cursor: pointer;"><i data-lucide="x" style="width: 18px;"></i></button>
                    </form>
                    @endif
                </div>
                @empty
                <p style="text-align: center; color: var(--text-muted); padding: 2rem;">Aucun point à l'ordre du jour.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Right Column: Participants & Report -->
    <div style="display: flex; flex-direction: column; gap: 2rem;">
        <!-- Participants Card -->
        <div class="card">
            <h3 style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                <i data-lucide="users" style="color: var(--accent);"></i> Participants
            </h3>
            
            @if(auth()->user()->isAdmin())
            <div style="position: relative; margin-bottom: 2rem;">
                <div class="form-group" style="margin-bottom: 0.5rem; position: relative;">
                    <i data-lucide="search" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); width: 16px; color: var(--text-muted);"></i>
                    <input type="text" id="participant-search" placeholder="Chercher un participant..." style="padding-left: 3rem; background: rgba(0,0,0,0.3);">
                </div>
                <div id="search-results" style="display: none; position: absolute; top: 100%; left: 0; right: 0; background: #0f172a; border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; z-index: 10; max-height: 250px; overflow-y: auto; box-shadow: 0 10px 25px rgba(0,0,0,0.5);">
                    @foreach(App\Models\User::where('role', '!=', 'admin')->get() as $user)
                        @if(!$reunion->participants->contains($user->id))
                        <div class="search-item" data-id="{{ $user->id }}" data-name="{{ strtolower($user->name) }}" style="padding: 0.75rem 1rem; cursor: pointer; border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: space-between;">
                            <span>{{ $user->name }}</span>
                            <form action="{{ route('participants.invite', $reunion) }}" method="POST">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                <button type="submit" style="background: var(--accent); color: var(--bg-dark); border: none; padding: 4px 10px; border-radius: 6px; cursor: pointer; font-size: 0.75rem; font-weight: bold;">Inviter +</button>
                            </form>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <script>
                document.getElementById('participant-search').addEventListener('input', function() {
                    const val = this.value.toLowerCase();
                    const results = document.getElementById('search-results');
                    const items = results.querySelectorAll('.search-item');
                    let hasResults = false;

                    if (val.length > 0) {
                        results.style.display = 'block';
                        items.forEach(item => {
                            if (item.dataset.name.includes(val)) {
                                item.style.display = 'flex';
                                hasResults = true;
                            } else {
                                item.style.display = 'none';
                            }
                        });
                        
                        if (!hasResults) results.style.display = 'none';
                    } else {
                        results.style.display = 'none';
                    }
                });

                // Hide results when clicking outside
                document.addEventListener('click', function(e) {
                    if (!e.target.closest('#participant-search') && !e.target.closest('#search-results')) {
                        document.getElementById('search-results').style.display = 'none';
                    }
                });
            </script>
            @endif

            <div style="display: flex; flex-direction: column; gap: 1rem;">
                @foreach($reunion->participants as $participant)
                <div style="display: flex; align-items: center; justify-content: space-between; font-size: 0.9rem;">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--bg-dark); border: 1px solid var(--accent); display: flex; align-items: center; justify-content: center; font-size: 0.8rem; color: var(--accent);">
                            {{ substr($participant->name, 0, 1) }}
                        </div>
                        <div>
                            <div>{{ $participant->name }}</div>
                            <div style="font-size: 0.7rem; color: var(--text-muted);">{{ ucfirst($participant->pivot->response_status) }}</div>
                        </div>
                    </div>
                    @if(auth()->user()->isAdmin())
                    <div style="display: flex; gap: 0.25rem;">
                        <form action="{{ route('participants.presence', [$reunion, $participant]) }}" method="POST">
                            @csrf
                            <input type="hidden" name="presence" value="1">
                            <button title="Présent" style="background: {{ $participant->pivot->presence === 1 ? 'var(--success)' : 'transparent' }}; border: 1px solid var(--success); color: {{ $participant->pivot->presence === 1 ? 'white' : 'var(--success)' }}; border-radius: 4px; padding: 2px; cursor: pointer;">
                                <i data-lucide="check" style="width: 14px;"></i>
                            </button>
                        </form>
                        <form action="{{ route('participants.presence', [$reunion, $participant]) }}" method="POST">
                            @csrf
                            <input type="hidden" name="presence" value="0">
                            <button title="Absent" style="background: {{ $participant->pivot->presence === 0 ? 'var(--danger)' : 'transparent' }}; border: 1px solid var(--danger); color: {{ $participant->pivot->presence === 0 ? 'white' : 'var(--danger)' }}; border-radius: 4px; padding: 2px; cursor: pointer;">
                                <i data-lucide="x" style="width: 14px;"></i>
                            </button>
                        </form>
                    </div>
                    @else
                        @if($participant->pivot->presence === 1)
                            <span style="color: var(--success); font-size: 0.7rem;">Présent <i data-lucide="check" style="width: 10px;"></i></span>
                        @elseif($participant->pivot->presence === 0)
                            <span style="color: var(--danger); font-size: 0.7rem;">Absent <i data-lucide="x" style="width: 10px;"></i></span>
                        @endif
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        <!-- Compte Rendu Card -->
        <div class="card" style="background: rgba(212, 175, 55, 0.05); border-color: rgba(212, 175, 55, 0.1);">
            <h3 style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                <i data-lucide="file-text" style="color: var(--accent);"></i> Compte Rendu
            </h3>

            @if($reunion->compteRendu)
            <div style="background: rgba(255,255,255,0.05); padding: 1rem; border-radius: 12px; display: flex; flex-direction: column; gap: 1rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <i data-lucide="file" style="color: var(--accent);"></i>
                    <div style="flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        {{ $reunion->compteRendu->file_name }}
                    </div>
                </div>
                <div style="display: flex; gap: 0.5rem; width: 100%;">
                    <a href="{{ route('reports.download', $reunion->compteRendu) }}" class="btn btn-primary" style="flex: 1; justify-content: center;">
                        <i data-lucide="download"></i> Télécharger
                    </a>
                    @if(auth()->user()->isAdmin())
                    <form action="{{ route('reports.destroy', $reunion->compteRendu) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce compte rendu pour le remplacer ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline" style="border-color: var(--danger); color: var(--danger); padding: 0.6rem 1rem;">
                            <i data-lucide="trash-2"></i>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @else
                <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.5rem;">Aucun compte rendu n'a été publié pour cette réunion.</p>
                @if(auth()->user()->isAdmin())
                <form action="{{ route('reports.store', $reunion) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <input type="file" name="file" required style="font-size: 0.8rem; padding: 0.5rem;">
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                        <i data-lucide="upload"></i> Publier le CR
                    </button>
                </form>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
