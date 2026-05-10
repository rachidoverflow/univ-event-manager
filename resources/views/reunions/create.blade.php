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
                <div id="instance-members-display" style="margin-top: 1.25rem; display: none; padding: 1rem; background: #f8fafc; border: 1px solid var(--border-color); border-radius: var(--radius-md);">
                    <p style="font-size: 0.65rem; color: var(--text-muted); margin-bottom: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">Membres de l'instance sélectionnés automatiquement :</p>
                    <div id="members-list" style="display: flex; flex-wrap: wrap; gap: 0.6rem;">
                        <!-- Members will be injected here -->
                    </div>
                </div>
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

        <div id="extra-participants-section" style="display: {{ old('type') == 'elargie' ? 'block' : 'none' }}; margin-top: 2rem; background: #ffffff; padding: 1.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
            <h3 style="font-size: 0.9rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--accent); font-weight: 700;">
                <i data-lucide="user-plus" style="width: 18px;"></i> Inviter des participants supplémentaires
            </h3>
            <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 1.25rem;">Sélectionnez les personnes à ajouter en plus des membres de l'instance.</p>
            
            <div style="max-height: 250px; overflow-y: auto; display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 0.75rem; padding-right: 0.5rem;">
                @foreach($users as $user)
                <label class="participant-option" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #f8fafc; border-radius: 8px; cursor: pointer; border: 1px solid var(--border-color); transition: var(--transition);">
                    <input type="checkbox" name="extra_participants[]" value="{{ $user->id }}" 
                        {{ is_array(old('extra_participants')) && in_array($user->id, old('extra_participants')) ? 'checked' : '' }}
                        style="width: 16px; height: 16px; accent-color: var(--accent);">
                    <div style="display: flex; flex-direction: column;">
                        <span style="font-size: 0.85rem; font-weight: 600; color: var(--text-main);">{{ $user->name }}</span>
                        <span style="font-size: 0.7rem; color: var(--text-muted);">{{ ucfirst($user->role) }}</span>
                    </div>
                </label>
                @endforeach
            </div>

            <style>
                .participant-option:hover {
                    border-color: var(--accent);
                    background: #ffffff;
                }
                .participant-option has(input:checked) {
                    border-color: var(--accent);
                    background: rgba(79, 70, 229, 0.05);
                }
            </style>
        </div>

        <div style="margin-top: 2rem; background: #ffffff; padding: 1.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-color); padding-bottom: 1rem;">
                <h3 style="font-size: 0.9rem; display: flex; align-items: center; gap: 0.5rem; color: var(--accent); font-weight: 700;">
                    <i data-lucide="list-checks" style="width: 18px;"></i> Ordre du jour initial
                </h3>
                <button type="button" id="add-agenda-point" class="btn btn-outline" style="font-size: 0.8rem; padding: 0.4rem 1.25rem; border-color: var(--accent); color: var(--accent);">
                    <i data-lucide="plus" style="width: 14px;"></i> Ajouter un point
                </button>
            </div>
            
            <div id="agenda-container" style="display: flex; flex-direction: column; gap: 1rem;">
                <!-- Points will be added here -->
            </div>
        </div>

        <template id="agenda-point-template">
            <div class="agenda-item animate-fade" style="display: flex; gap: 1rem; background: #f8fafc; padding: 1.25rem; border-radius: 8px; border: 1px solid var(--border-color); position: relative;">
                <div style="width: 24px; height: 24px; background: var(--accent); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700; flex-shrink: 0; margin-top: 0.5rem;">
                    #
                </div>
                <div style="flex: 1; display: flex; flex-direction: column; gap: 0.75rem;">
                    <input type="text" name="agenda[INDEX][titre]" placeholder="Titre du point (ex: Rapport financier...)" required style="border-radius: 4px; font-weight: 600;">
                    <textarea name="agenda[INDEX][description]" placeholder="Description ou détails (optionnel)" rows="2" style="font-size: 0.85rem; border-radius: 4px;"></textarea>
                </div>
                <button type="button" class="remove-point" style="background: #fee2e2; border: none; color: #dc2626; cursor: pointer; padding: 0.5rem; border-radius: 6px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; margin-top: 0.5rem; transition: var(--transition);">
                    <i data-lucide="trash-2" style="width: 16px;"></i>
                </button>
            </div>
        </template>

        <script>
            let agendaIndex = 0;
            const container = document.getElementById('agenda-container');
            const template = document.getElementById('agenda-point-template');

            function updateAgendaNumbers() {
                const items = container.querySelectorAll('.agenda-item');
                items.forEach((item, idx) => {
                    const numDiv = item.querySelector('div');
                    if (numDiv && numDiv.textContent.trim() !== '') {
                        numDiv.textContent = idx + 1;
                    }
                });
            }

            document.getElementById('add-agenda-point').addEventListener('click', () => {
                const clone = template.content.cloneNode(true);
                const html = clone.firstElementChild.innerHTML.replace(/INDEX/g, agendaIndex);
                clone.firstElementChild.innerHTML = html;
                
                const currentItem = clone.firstElementChild;
                currentItem.querySelector('.remove-point').addEventListener('click', () => {
                    currentItem.remove();
                    updateAgendaNumbers();
                });

                container.appendChild(clone);
                agendaIndex++;
                updateAgendaNumbers();
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
            document.getElementById('instance_id').addEventListener('change', function() {
                const instanceId = this.value;
                const display = document.getElementById('instance-members-display');
                const list = document.getElementById('members-list');
                
                if (!instanceId) {
                    display.style.display = 'none';
                    return;
                }

                const baseUrl = "{{ url('/') }}";
                list.innerHTML = '<span style="font-size: 0.8rem; color: var(--text-muted);">Chargement...</span>';
                display.style.display = 'block';

                fetch(`${baseUrl}/api/instances/${instanceId}/members`)
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(members => {
                        list.innerHTML = '';
                        if (members.length === 0) {
                            list.innerHTML = '<span style="font-size: 0.8rem; color: var(--text-muted); font-style: italic;">Aucun membre rattaché</span>';
                        } else {
                            members.forEach(member => {
                                const name = member.user ? member.user.name : member.guest_name;
                                const isGuest = !member.user;
                                
                                const div = document.createElement('div');
                                div.style.cssText = `
                                    display: flex; 
                                    align-items: center; 
                                    gap: 0.5rem; 
                                    padding: 6px 12px; 
                                    background: white; 
                                    border: 1px solid var(--border-color); 
                                    border-radius: 30px; 
                                    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
                                    transition: var(--transition);
                                `;
                                
                                const avatar = document.createElement('div');
                                avatar.style.cssText = `
                                    width: 20px; 
                                    height: 20px; 
                                    border-radius: 50%; 
                                    background: ${isGuest ? '#f8fafc' : 'var(--accent)'}; 
                                    color: ${isGuest ? '#64748b' : 'white'}; 
                                    display: flex; 
                                    align-items: center; 
                                    justify-content: center; 
                                    font-size: 0.6rem; 
                                    font-weight: 700;
                                `;
                                avatar.textContent = name.charAt(0);
                                
                                const nameSpan = document.createElement('span');
                                nameSpan.style.cssText = 'font-size: 0.75rem; font-weight: 500; color: var(--text-main);';
                                nameSpan.textContent = name;
                                
                                div.appendChild(avatar);
                                div.appendChild(nameSpan);
                                list.appendChild(div);
                            });
                        }
                        display.style.display = 'block';
                        display.classList.add('animate-fade');
                    })
                    .catch(error => {
                        console.error('Erreur lors de la récupération des membres:', error);
                    });
            });

            // Trigger on page load if old value exists
            if (document.getElementById('instance_id').value) {
                document.getElementById('instance_id').dispatchEvent(new Event('change'));
            }
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
