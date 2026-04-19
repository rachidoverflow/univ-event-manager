<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Univ-Event Manager - @yield('title', 'Faculté de Gestion')</title>
    <meta name="description" content="Système de gestion des réunions pour la faculté.">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons (CDN) -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    @auth
    <aside class="sidebar">
        <div class="logo">
            <i data-lucide="graduation-cap"></i>
            <span>UnivEvents</span>
        </div>
        
        <nav>
            <div class="sidebar-section-title">
                Gestion générale
            </div>
            <ul class="nav-links">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i data-lucide="layout-dashboard"></i> Tableau de bord
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('reunions.index') }}" class="nav-link {{ request()->routeIs('reunions.*') ? 'active' : '' }}">
                        <i data-lucide="calendar"></i> Réunions
                    </a>
                </li>
                @if(auth()->user()->isAdmin())
                <li class="nav-item">
                    <a href="{{ route('participants.index') }}" class="nav-link {{ request()->routeIs('participants.*') ? 'active' : '' }}">
                        <i data-lucide="users"></i> Participants
                    </a>
                </li>
                @endif
            </ul>

            <div class="sidebar-section-title" id="commissions-toggle" style="cursor: pointer; display: flex; justify-content: space-between; align-items: center; padding-right: 1.5rem; margin-top: 2rem;">
                Commissions / Instances
                <i data-lucide="chevron-down" id="commissions-arrow" style="width: 14px; transition: transform 0.3s;"></i>
            </div>
            <ul class="nav-links" id="commissions-list" style="display: none; padding-left: 0.5rem; transition: all 0.3s ease;">
                @foreach($instances as $instance)
                <li class="nav-item">
                    <a href="{{ route('instances.show', $instance) }}" class="nav-link {{ request()->fullUrl() == route('instances.show', $instance) ? 'active' : '' }}" style="font-size: 0.85rem; padding: 0.6rem 1rem;">
                        {{ $instance->nom }}
                    </a>
                </li>
                @endforeach
            </ul>
        </nav>

        <script>
            document.getElementById('commissions-toggle').addEventListener('click', function() {
                const list = document.getElementById('commissions-list');
                const arrow = document.getElementById('commissions-arrow');
                const isHidden = list.style.display === 'none';
                
                list.style.display = isHidden ? 'block' : 'none';
                arrow.style.transform = isHidden ? 'rotate(180deg)' : 'rotate(0deg)';
                
                // Save state in localStorage if you want it to persist
                localStorage.setItem('commissions_expanded', isHidden);
            });

            // Restore state
            if (localStorage.getItem('commissions_expanded') === 'true') {
                document.getElementById('commissions-list').style.display = 'block';
                document.getElementById('commissions-arrow').style.transform = 'rotate(180deg)';
            }
        </script>

        <div style="margin-top: auto;">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-link" style="background: none; border: none; width: 100%; cursor: pointer;">
                    <i data-lucide="log-out"></i> Déconnexion
                </button>
            </form>
        </div>
    </aside>
    @endauth

    <main class="{{ Auth::check() ? 'main-content' : '' }}">
        @auth
        <header>
            <h1 class="animate-fade">@yield('page-title', 'Bienvenue')</h1>
            <div style="display: flex; align-items: center; gap: 1.5rem;">
                <!-- Notifications -->
                <div style="position: relative;" id="notif-wrapper">
                    <button id="notif-bell" style="background: none; border: none; color: var(--text-muted); cursor: pointer; position: relative; padding: 0.5rem; display: flex; align-items: center;">
                        <i data-lucide="bell" style="width: 22px;"></i>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span style="position: absolute; top: 4px; right: 4px; width: 8px; height: 8px; background: var(--danger); border-radius: 50%; border: 2px solid white;"></span>
                        @endif
                    </button>

                    <!-- Dropdown -->
                    <div id="notif-dropdown" style="display: none; position: absolute; right: 0; top: 100%; width: 320px; background: white; border: 1px solid var(--border-color); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); z-index: 1000; margin-top: 0.5rem; border-radius: var(--radius-md);">
                        <div style="padding: 1rem; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
                            <h4 style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; color: var(--text-muted);">Notifications</h4>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <form action="{{ route('notifications.read-all') }}" method="POST">
                                    @csrf
                                    <button type="submit" style="background: none; border: none; color: var(--accent); font-size: 0.75rem; font-weight: 600; cursor: pointer;">Tout lire</button>
                                </form>
                            @endif
                        </div>
                        <div style="max-height: 400px; overflow-y: auto;">
                            @forelse(auth()->user()->notifications->take(10) as $notif)
                                <div style="padding: 1rem; border-bottom: 1px solid #f1f5f9; position: relative; {{ $notif->read_at ? 'opacity: 0.6;' : 'background: rgba(113, 75, 103, 0.02);' }}">
                                    <div style="display: flex; align-items: flex-start; gap: 0.75rem;">
                                        <div style="margin-top: 2px;">
                                            @if($notif->data['type'] == 'invitation')
                                                <i data-lucide="calendar" style="width: 14px; color: var(--primary);"></i>
                                            @else
                                                <i data-lucide="file-text" style="width: 14px; color: var(--success);"></i>
                                            @endif
                                        </div>
                                        <div style="flex: 1;">
                                            <div style="font-size: 0.85rem; font-weight: 700; margin-bottom: 0.2rem; color: var(--text-main);">{{ $notif->data['title'] }}</div>
                                            <div style="font-size: 0.8rem; line-height: 1.4; color: var(--text-muted);">{{ $notif->data['message'] }}</div>
                                            <div style="margin-top: 0.5rem; display: flex; justify-content: space-between; align-items: center;">
                                                <a href="{{ $notif->data['action_url'] }}" style="font-size: 0.75rem; color: var(--accent); text-decoration: none; font-weight: 600;">Voir détails</a>
                                                <span style="font-size: 0.7rem; color: #94a3b8;">{{ $notif->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    @if(!$notif->read_at)
                                        <form action="{{ route('notifications.read', $notif->id) }}" method="POST" style="position: absolute; top: 1rem; right: 0.5rem;">
                                            @csrf
                                            <button type="submit" title="Marquer comme lu" style="background: none; border: none; color: #cbd5e1; cursor: pointer;">
                                                <i data-lucide="x" style="width: 12px;"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @empty
                                <div style="padding: 2rem; text-align: center; color: var(--text-muted); font-size: 0.85rem;">
                                    <i data-lucide="bell-off" style="width: 24px; opacity: 0.2; margin-bottom: 0.5rem;"></i>
                                    <p>Aucune notification</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="user-profile animate-fade">
                    <span>{{ auth()->user()->name }}</span>
                    <div style="background: var(--accent); width: 36px; height: 36px; border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center; color: #000; font-weight: bold; font-size: 0.9rem;">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                </div>
            </div>
        </header>
        @endauth

        @if(session('success'))
            <div class="animate-fade" style="background: rgba(16, 185, 129, 0.1); color: var(--success); padding: 1rem; border-radius: 12px; margin-bottom: 2rem; border: 1px solid var(--success);">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="animate-fade" style="background: rgba(239, 68, 68, 0.1); color: var(--danger); padding: 1rem; border-radius: 12px; margin-bottom: 2rem; border: 1px solid var(--danger);">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="animate-fade" style="background: rgba(239, 68, 68, 0.1); color: var(--danger); padding: 1rem; border-radius: 12px; margin-bottom: 2rem; border: 1px solid var(--danger);">
                <ul style="margin: 0; padding-left: 1.5rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Notification Dropdown Toggle
        const bell = document.getElementById('notif-bell');
        const dropdown = document.getElementById('notif-dropdown');

        if (bell) {
            bell.addEventListener('click', (e) => {
                e.stopPropagation();
                dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
            });
        }

        document.addEventListener('click', (e) => {
            if (dropdown && !dropdown.contains(e.target) && e.target !== bell) {
                dropdown.style.display = 'none';
            }
        });
    </script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
