<aside class="sidebar" id="sidebar">
 <div class="side-header">
  <div class="logo">
    <div class="logo-box">
      @if($globalSetting && $globalSetting->logo)
        <img src="{{ asset('storage/'.$globalSetting->logo) }}" alt="Logo" class="logo-img">
      @endif
    </div>
    <div class="logo-text-wrap">
      <div class="logo-title">{{ $globalSetting->institution_short ?? '' }}</div>
      <div class="logo-subtitle">{{ $globalSetting->institution_name ?? '' }}</div>
    </div>
  </div>
</div>

  <div class="side-menu">
    <div class="menu-group">
      <div class="menu-label">Menu</div>
      <a href="dashboard" class="menu-item {{ request()->is('dashboard') ? 'active' : '' }}">
      <i class="fas fa-chart-pie"></i>
      <span>Dashboard</span>
    </a>
    <a href="guests" class="menu-item {{ request()->is('guests') ? 'active' : '' }}">
      <i class="fas fa-users"></i>
      <span>Kunjungan</span>
    </a>
    </div>

    <div class="menu-group">
      <div class="menu-label">Lainnya</div>
        <a href="users" class="menu-item {{ request()->is('users') ? 'active' : '' }}">
            <i class="fas fa-users-cog"></i>
            <span>Pengguna</span>
        </a>
        <a href="/settings" class="menu-item {{ request()->is('settings') ? 'active' : '' }}">
            <i class="fas fa-cog"></i>
            <span>Pengaturan</span>
        </a>
    </div>
  </div>

 <div class="side-footer">
  <a href="#" class="logout-btn" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
    <i class="fas fa-sign-out-alt"></i>
    <span>Keluar</span>
  </a>
</div>
</aside>
