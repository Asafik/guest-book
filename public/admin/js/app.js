(function () {
  const body = document.body;
  const sidebar = document.getElementById('sidebar');
  const navbarToggle = document.getElementById('navbarToggle');
  const mobileMenu = document.getElementById('mobileMenu');
  const overlay = document.getElementById('overlay');

  const profileBtn = document.getElementById('profileBtn');
  const dropdown = document.getElementById('dropdown');

  const fullscreenIcon = document.getElementById('fullscreenIcon');
  const messageIcon = document.getElementById('messageIcon');
  const notifIcon = document.getElementById('notifIcon');

  const currentYearSpan = document.getElementById('currentYear');

  // ===== CEK APAKAH MOBILE =====
  function isMobile() {
    return window.innerWidth <= 768;
  }

  // ===== SIDEBAR FUNCTIONS =====
  function closeMobileSidebar() {
    if (sidebar) {
      sidebar.classList.remove('mobile-open');
    }
    if (overlay) {
      overlay.classList.remove('show');
    }
    body.style.overflow = '';
  }

  function openMobileSidebar() {
    if (sidebar) {
      sidebar.classList.add('mobile-open');
    }
    if (overlay) {
      overlay.classList.add('show');
    }
    body.style.overflow = 'hidden';
  }

  // ===== TOGGLE SIDEBAR (DESKTOP) =====
  if (navbarToggle) {
    navbarToggle.addEventListener('click', function () {
      if (!isMobile()) {
        sidebar.classList.toggle('collapsed');
      }
    });
  }

  // ===== MOBILE MENU =====
  if (mobileMenu) {
    mobileMenu.addEventListener('click', function () {
      if (sidebar.classList.contains('mobile-open')) {
        closeMobileSidebar();
      } else {
        openMobileSidebar();
      }
    });
  }

  // ===== OVERLAY CLICK =====
  if (overlay) {
    overlay.addEventListener('click', function () {
      closeMobileSidebar();
      if (dropdown) {
        dropdown.classList.remove('show');
      }
    });
  }

  // ===== RESIZE HANDLER =====
  window.addEventListener('resize', function () {
    if (!isMobile()) {
      closeMobileSidebar();
    }
  });

  // ===== PROFILE DROPDOWN =====
  if (profileBtn && dropdown) {
    profileBtn.addEventListener('click', function (e) {
      e.stopPropagation();
      dropdown.classList.toggle('show');
    });
  }

  // ===== CLICK OUTSIDE DROPDOWN =====
  document.addEventListener('click', function (e) {
    if (profileBtn && dropdown) {
      if (!profileBtn.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.classList.remove('show');
      }
    }
  });

  // ===== FULLSCREEN FUNCTION =====
  function updateFullscreenIcon() {
    if (fullscreenIcon) {
      if (document.fullscreenElement) {
        fullscreenIcon.innerHTML = '<i class="fas fa-compress"></i>';
      } else {
        fullscreenIcon.innerHTML = '<i class="fas fa-expand"></i>';
      }
    }
  }

  function toggleFullscreen() {
    if (!document.fullscreenElement) {
      document.documentElement.requestFullscreen().catch(function (err) {
        console.log('Gagal masuk fullscreen:', err.message);
      });
    } else {
      document.exitFullscreen();
    }
  }

  if (fullscreenIcon) {
    fullscreenIcon.addEventListener('click', toggleFullscreen);
  }

  document.addEventListener('fullscreenchange', updateFullscreenIcon);

  // ===== NOTIFICATION BUTTONS =====
  if (messageIcon) {
    messageIcon.addEventListener('click', function () {
      // Bisa diganti dengan fungsi notifikasi yang lebih kompleks
      console.log('Message icon clicked');
      // alert('Anda memiliki 3 pesan baru'); // Uncomment jika ingin alert
    });
  }

  if (notifIcon) {
    notifIcon.addEventListener('click', function () {
      // Bisa diganti dengan fungsi notifikasi yang lebih kompleks
      console.log('Notification icon clicked');
      // alert('Anda memiliki 5 notifikasi baru'); // Uncomment jika ingin alert
    });
  }

  // ===== TAHUN REALTIME DI FOOTER =====
  if (currentYearSpan) {
    currentYearSpan.textContent = new Date().getFullYear();
  }

  // Atau jika ingin menggunakan format write langsung
  // Bisa juga untuk footer yang menggunakan script write
  const yearElements = document.querySelectorAll('.current-year');
  yearElements.forEach(el => {
    el.textContent = new Date().getFullYear();
  });

  // ===== CLEANUP =====
  window.addEventListener('beforeunload', function () {
    // Cleanup jika ada stream camera atau yang lain
    // (untuk kompatibilitas dengan halaman form)
  });

  // ===== ESC KEY =====
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      // Tutup dropdown jika ada
      if (dropdown && dropdown.classList.contains('show')) {
        dropdown.classList.remove('show');
      }

      // Tutup mobile sidebar jika ada
      if (isMobile() && sidebar && sidebar.classList.contains('mobile-open')) {
        closeMobileSidebar();
      }

      // Keluar dari fullscreen jika sedang fullscreen
      if (document.fullscreenElement) {
        document.exitFullscreen();
      }
    }
  });

})();
