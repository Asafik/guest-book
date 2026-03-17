<header class="site-header">
    <div class="container nav-wrap">
        <a href="/" class="brand">
            <div class="brand-logo-img">
                <img
                    src="{{ $globalSetting && $globalSetting->logo ? asset('storage/'.$globalSetting->logo) : asset('jcc.png') }}"
                    alt="{{ $globalSetting->institution_name ?? 'Jember Command Center' }}"
                    onerror="this.style.display='none'; this.parentElement.innerHTML='<i class=\'fas fa-landmark\' style=\'font-size:28px;color:#ff679a;\'></i>';"
                >
            </div>
            <div class="brand-text">
                <strong>{{ $globalSetting->institution_name ?? 'Jember Command Center' }}</strong>
                <span>{{ $globalSetting->app_name ?? 'Buku Tamu Digital' }}</span>
            </div>
        </a>

        <nav class="nav-menu" id="navMenu">
            <a href="/" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                <i class="fas fa-home"></i> Beranda
                <span class="active-badge" style="{{ request()->is('/') ? 'display: inline-block;' : 'display: none;' }}"></span>
            </a>

            <a href="/formulir" class="nav-link {{ request()->is('formulir') ? 'display: inline-block;' : 'display: none;' }}">
                <i class="fas fa-pen"></i> Formulir
                <span class="active-badge" style="{{ request()->is('formulir') ? 'display: inline-block;' : 'display: none;' }}"></span>
            </a>
        </nav>

        <button class="menu-toggle" id="menuToggle" aria-label="Toggle menu">
            <i class="fas fa-bars"></i>
        </button>
    </div>
</header>
