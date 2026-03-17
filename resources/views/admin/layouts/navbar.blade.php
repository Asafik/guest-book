<nav class="topnav">
  <div class="nav-left">
    <button class="nav-toggle" id="navbarToggle" aria-label="Toggle Sidebar">
      <i class="fas fa-bars"></i>
    </button>

    <button class="mobile-menu" id="mobileMenu" aria-label="Open Menu">
      <i class="fas fa-bars"></i>
    </button>

    <div class="page-head">
      <span class="page-title">{{ $pageTitle ?? '-' }}</span>
    </div>
  </div>

  <div class="nav-right">
    <button class="nav-icon" id="fullscreenIcon" aria-label="Fullscreen">
      <i class="fas fa-expand"></i>
    </button>

    <div class="profile" id="profileDropdown">
      <button class="profile-btn" id="profileBtn" aria-label="Profile Menu">
        <div class="profile-avatar">AD</div>
        <div class="profile-meta">
          <span class="profile-name">Admin Dinas</span>
          <span class="profile-role">Super Admin</span>
        </div>
        <i class="fas fa-chevron-down profile-arrow"></i>
      </button>

      <div class="dropdown" id="dropdown">
        <a href="{{ route('admin.settings') }}" class="dropdown-item">
          <i class="fas fa-cog"></i>
          <span>Pengaturan</span>
        </a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
          <i class="fas fa-sign-out-alt"></i>
          <span>Keluar</span>
        </a>
        <form id="logoutForm" method="POST" action="{{ route('logout') }}" style="display:none;">
          @csrf
        </form>
      </div>
    </div>
  </div>
</nav>
