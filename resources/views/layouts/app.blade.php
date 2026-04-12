<!DOCTYPE html>
<html lang="fr" class="dark">
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
                    <a href="#" class="nav-link">
                        <i data-lucide="users"></i> Participants
                    </a>
                </li>
                @endif
            </ul>
        </nav>

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
            <div class="user-profile animate-fade">
                <span>{{ auth()->user()->name }}</span>
                <div style="background: var(--accent); width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--bg-dark); font-weight: bold;">
                    {{ substr(auth()->user()->name, 0, 1) }}
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

        @yield('content')
    </main>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();
    </script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
