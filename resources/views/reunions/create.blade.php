@extends('layouts.app')

@section('title', 'Nouvelle Réunion')
@section('page-title', 'Créer une Réunion')

@section('content')
<div class="card animate-fade" style="max-width: 800px; margin: 0 auto;">
    <form action="{{ route('reunions.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="titre">Titre de la réunion</label>
            <input type="text" id="titre" name="titre" required placeholder="Ex: Bureau décanat, Commission recherche..." value="{{ old('titre') }}">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label for="instance_id">Instance / Commission</label>
                <select id="instance_id" name="instance_id">
                    <option value="">-- Sélectionner une instance --</option>
                    @foreach($instances as $instance)
                        <option value="{{ $instance->id }}" {{ old('instance_id') == $instance->id ? 'selected' : '' }}>{{ $instance->nom }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="type">Type de Réunion</label>
                <select id="type" name="type" required>
                    <option value="standard" {{ old('type') == 'standard' ? 'selected' : '' }}>Standard (Membres instance)</option>
                    <option value="elargie" {{ old('type') == 'elargie' ? 'selected' : '' }}>Élargie (Membres + Invités)</option>
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-top: 1rem;">
            <div class="form-group">
                <label for="date">Date tenue</label>
                <input type="date" id="date" name="date" required value="{{ old('date') }}">
            </div>
            <div class="form-group">
                <label for="lieu">Lieu / Salle</label>
                <input type="text" id="lieu" name="lieu" placeholder="Ex: Salle des actes, Amphi A..." value="{{ old('lieu') }}">
            </div>
        </div>

        <div class="form-group" style="margin-top: 1rem;">
            <label for="invitation_content">Contenu de l'invitation (Optionnel)</label>
            <textarea id="invitation_content" name="invitation_content" rows="4" placeholder="Message personnalisé à envoyer aux participants...">{{ old('invitation_content') }}</textarea>
        </div>

        <div id="extra-participants-section" style="display: {{ old('type') == 'elargie' ? 'block' : 'none' }}; margin-top: 2rem; background: rgba(0,0,0,0.2); padding: 1.5rem; border-radius: 12px; border: 1px dashed rgba(212, 175, 55, 0.2);">
            <h3 style="font-size: 1rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--accent);">
                <i data-lucide="user-plus"></i> Inviter d'autres participants
            </h3>
            <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 1rem;">Sélectionnez les personnes supplémentaires à inviter (hors membres de l'instance).</p>
            
            <div style="max-height: 200px; overflow-y: auto; display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 0.75rem; padding-right: 0.5rem;">
                @foreach($users as $user)
                <label style="display: flex; align-items: center; gap: 0.75rem; padding: 0.5rem; background: rgba(255,255,255,0.03); border-radius: 8px; cursor: pointer; border: 1px solid rgba(255,255,255,0.05);">
                    <input type="checkbox" name="extra_participants[]" value="{{ $user->id }}" {{ is_array(old('extra_participants')) && in_array($user->id, old('extra_participants')) ? 'checked' : '' }}>
                    <span style="font-size: 0.85rem;">{{ $user->name }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <div style="margin-top: 2rem; background: rgba(255,255,255,0.02); padding: 1.5rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="font-size: 1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--accent);">
                    <i data-lucide="list-checks"></i> Ordre du jour initial
                </h3>
                <button type="button" id="add-agenda-point" class="btn btn-outline" style="font-size: 0.8rem; padding: 0.4rem 1rem;">
                    <i data-lucide="plus"></i> Ajouter un point
                </button>
            </div>
            
            <div id="agenda-container" style="display: flex; flex-direction: column; gap: 1rem;">
                <!-- Points will be added here -->
            </div>
        </div>

        <template id="agenda-point-template">
            <div class="agenda-item animate-fade" style="display: flex; gap: 1rem; background: rgba(0,0,0,0.2); padding: 1rem; border-radius: 8px; position: relative;">
                <div style="flex: 1; display: flex; flex-direction: column; gap: 0.75rem;">
                    <input type="text" name="agenda[INDEX][titre]" placeholder="Titre du point (ex: Rapport financier...)" required style="background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1);">
                    <textarea name="agenda[INDEX][description]" placeholder="Description ou détails (optionnel)" rows="2" style="background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1); font-size: 0.9rem;"></textarea>
                </div>
                <button type="button" class="remove-point" style="background: none; border: none; color: var(--danger); cursor: pointer; padding: 0.5rem;">
                    <i data-lucide="trash-2" style="width: 18px;"></i>
                </button>
            </div>
        </template>

        <script>
            let agendaIndex = 0;
            const container = document.getElementById('agenda-container');
            const template = document.getElementById('agenda-point-template');

            document.getElementById('add-agenda-point').addEventListener('click', () => {
                const clone = template.content.cloneNode(true);
                const html = clone.firstElementChild.innerHTML.replace(/INDEX/g, agendaIndex);
                clone.firstElementChild.innerHTML = html;
                
                const currentItem = clone.firstElementChild;
                currentItem.querySelector('.remove-point').addEventListener('click', () => {
                    currentItem.remove();
                });

                container.appendChild(clone);
                agendaIndex++;
                lucide.createIcons();
            });

            document.getElementById('type').addEventListener('change', function() {
                const section = document.getElementById('extra-participants-section');
                if (this.value === 'elargie') {
                    section.style.display = 'block';
                    section.classList.add('animate-fade');
                } else {
                    section.style.display = 'none';
                }
            });
        </script>

        <div style="margin-top: 2rem; display: flex; justify-content: flex-end; gap: 1rem; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 2rem;">
            <a href="{{ route('reunions.index') }}" class="btn btn-outline">Annuler</a>
            <button type="submit" class="btn btn-primary" style="padding: 0.8rem 2rem;">
                Créer la réunion <i data-lucide="check"></i>
            </button>
        </div>
    </form>
</div>
@endsection
