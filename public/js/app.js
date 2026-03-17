document.addEventListener('DOMContentLoaded', function () {
    // Ambil elemen tombol menu mobile
    const menuToggle = document.getElementById('menuToggle');

    // Ambil elemen menu navigasi
    const navMenu = document.getElementById('navMenu');

    // Ambil elemen tahun di footer
    const currentYear = document.getElementById('currentYear');

    // Set tahun footer otomatis sesuai tahun saat ini
    if (currentYear) {
        currentYear.textContent = new Date().getFullYear();
    }

    // Jalankan hanya jika tombol menu dan nav menu ada
    if (menuToggle && navMenu) {
        // Event klik tombol hamburger
        menuToggle.addEventListener('click', function () {
            // Toggle class open untuk buka/tutup menu mobile
            navMenu.classList.toggle('open');

            // Ganti icon hamburger jadi X saat menu terbuka
            menuToggle.innerHTML = navMenu.classList.contains('open')
                ? '<i class="fas fa-times"></i>'
                : '<i class="fas fa-bars"></i>';
        });

        // Ambil semua link di dalam navbar
        document.querySelectorAll('#navMenu a').forEach(function (link) {
            // Saat salah satu link diklik
            link.addEventListener('click', function () {
                // Tutup kembali menu mobile
                navMenu.classList.remove('open');

                // Kembalikan icon tombol ke hamburger
                menuToggle.innerHTML = '<i class="fas fa-bars"></i>';
            });
        });
    }
});
