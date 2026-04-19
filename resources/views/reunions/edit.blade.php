@extends('layouts.app')

@section('title', 'Modifier ' . $reunion->titre)
@section('page-title', 'Modifier la Réunion')

@section('content')
<div class="card animate-fade" style="max-width: 800px; margin: 0 auto;">
    <form action="{{ route('reunions.update', $reunion) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="titre">Titre de la réunion</label>
            <input type="text" id="titre" name="titre" required value="{{ old('titre', $reunion->titre) }}">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label for="instance_id">Instance / Commission</label>
                <select id="instance_id" name="instance_id">
                    <option value="">-- Sélectionner une instance --</option>
                    @foreach($instances as $instance)
                        <option value="{{ $instance->id }}" {{ old('instance_id', $reunion->instance_id) == $instance->id ? 'selected' : '' }}>{{ $instance->nom }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="type">Type de Réunion</label>
                <select id="type" name="type" required>
                    <option value="standard" {{ old('type', $reunion->type) == 'standard' ? 'selected' : '' }}>Standard (Membres instance)</option>
                    <option value="elargie" {{ old('type', $reunion->type) == 'elargie' ? 'selected' : '' }}>Élargie (Membres + Invités)</option>
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-top: 1rem;">
            <div class="form-group">
                <label for="date">Date tenue</label>
                <input type="date" id="date" name="date" required value="{{ old('date', $reunion->date) }}">
            </div>
            <div class="form-group">
                <label for="lieu">Lieu / Salle</label>
                <input type="text" id="lieu" name="lieu" value="{{ old('lieu', $reunion->lieu) }}">
            </div>
        </div>

        <div class="form-group">
            <label for="status">Statut de la réunion</label>
            <select name="status" id="status" required>
                <option value="planifiee" {{ $reunion->status == 'planifiee' ? 'selected' : '' }}>Planifiée</option>
                <option value="en_cours" {{ $reunion->status == 'en_cours' ? 'selected' : '' }}>En cours</option>
                <option value="terminee" {{ $reunion->status == 'terminee' ? 'selected' : '' }}>Terminée</option>
            </select>
        </div>

        <div id="extra-participants-section" style="display: {{ old('type', $reunion->type) == 'elargie' ? 'block' : 'none' }}; margin-top: 2rem; background: rgba(0,0,0,0.2); padding: 1.5rem; border-radius: 12px; border: 1px dashed rgba(212, 175, 55, 0.2);">
            <h3 style="font-size: 1rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--accent);">
                <i data-lucide="user-plus"></i> Inviter d'autres participants
            </h3>
            
            <div style="max-height: 200px; overflow-y: auto; display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 0.75rem; padding-right: 0.5rem;">
                @foreach($users as $user)
                <label style="display: flex; align-items: center; gap: 0.75rem; padding: 0.5rem; background: rgba(255,255,255,0.03); border-radius: 8px; cursor: pointer; border: 1px solid rgba(255,255,255,0.05);">
                    <input type="checkbox" name="extra_participants[]" value="{{ $user->id }}" 
                        {{ (is_array(old('extra_participants')) && in_array($user->id, old('extra_participants'))) || (!old('extra_participants') && in_array($user->id, $currentParticipantIds)) ? 'checked' : '' }}>
                    <span style="font-size: 0.85rem;">{{ $user->name }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <div style="margin-top: 2rem; background: rgba(255,255,255,0.02); padding: 1.5rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="font-size: 1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--accent);">
                    <i data-lucide="list-checks"></i> Ordre du jour
                </h3>
                <button type="button" id="add-agenda-point" class="btn btn-outline" style="font-size: 0.8rem; padding: 0.4rem 1rem;">
                    <i data-lucide="plus"></i> Ajouter un point
                </button>
            </div>
            
            <div id="agenda-container" style="display: flex; flex-direction: column; gap: 1rem;">
                @foreach($reunion->agendas as $index => $item)
                <div class="agenda-item animate-fade" style="display: flex; gap: 1rem; background: rgba(0,0,0,0.2); padding: 1rem; border-radius: 8px; position: relative;">
                    <div style="flex: 1; display: flex; flex-direction: column; gap: 0.75rem;">
                        <input type="text" name="agenda[{{ $index }}][titre]" value="{{ $item->titre }}" placeholder="Titre du point" required style="background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1);">
                        <textarea name="agenda[{{ $index }}][description]" placeholder="Description" rows="2" style="background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1); font-size: 0.9rem;">{{ $item->description }}</textarea>
                    </div>
                    <button type="button" class="remove-point" onclick="this.parentElement.remove()" style="background: none; border: none; color: var(--danger); cursor: pointer; padding: 0.5rem;">
                        <i data-lucide="trash-2" style="width: 18px;"></i>
                    </button>
                </div>
                @endforeach
            </div>
        </div>

        <template id="agenda-point-template">
            <div class="agenda-item animate-fade" style="display: flex; gap: 1rem; background: rgba(0,0,0,0.2); padding: 1rem; border-radius: 8px; position: relative;">
                <div style="flex: 1; display: flex; flex-direction: column; gap: 0.75rem;">
                    <input type="text" name="agenda[INDEX][titre]" placeholder="Titre du point" required style="background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1);">
                    <textarea name="agenda[INDEX][description]" placeholder="Description" rows="2" style="background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1); font-size: 0.9rem;"></textarea>
                </div>
                <button type="button" class="remove-point" style="background: none; border: none; color: var(--danger); cursor: pointer; padding: 0.5rem;">
                    <i data-lucide="trash-2" style="width: 18px;"></i>
                </button>
            </div>
        </template>

        <script>
            let agendaIndex = {{ $reunion->agendas->count() }};
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
            <a href="{{ route('reunions.show', $reunion) }}" class="btn btn-outline">Annuler</a>
            <button type="submit" class="btn btn-primary" style="padding: 0.8rem 2rem;">
                Mettre à jour <i data-lucide="save"></i>
            </button>
        </div>
    </form>
</div>
@endsection
