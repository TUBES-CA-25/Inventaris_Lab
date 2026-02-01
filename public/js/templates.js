// Templates JS Functions

// Sidebar Toggle Function (dari sidebar.php)
document.addEventListener('DOMContentLoaded', function() {
    const hamburgerBtn = document.getElementById('hamburgerBtn');
    const sidebarMenu = document.getElementById('sidebarMenu');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    // Fungsi buka/tutup
    function toggleSidebar() {
        sidebarMenu.classList.toggle('active');
        sidebarOverlay.classList.toggle('active');
        
        // Ubah icon hamburger jadi X (opsional)
        const icon = hamburgerBtn.querySelector('i');
        if (sidebarMenu.classList.contains('active')) {
            icon.classList.remove('fa-bars');
            icon.classList.add('fa-xmark');
        } else {
            icon.classList.remove('fa-xmark');
            icon.classList.add('fa-bars');
        }
    }

    // Event Listeners
    if(hamburgerBtn) {
        hamburgerBtn.addEventListener('click', toggleSidebar);
    }

    if(sidebarOverlay) {
        sidebarOverlay.addEventListener('click', toggleSidebar); // Tutup saat klik luar
    }
});

// Loader Functions (dari footer.php dan footer1.php)
$(function() {
    // 1. Logika Menghilangkan Loader setelah halaman Load
    $(window).on('load', function() {
        // Beri sedikit delay (200ms) agar transisi hilangnya halus
        setTimeout(function() {
            $('#loading-screen').addClass('hidden');
        }, 200);
    });

    // 2. Logika Memunculkan Loader saat navigasi fitur diklik
    $(document).ready(function() {
        // Targetkan semua link di menu-item sidebar
        $('.menu-item').on('click', function(e) {
            
            // Cek: Jangan munculkan loader jika:
            // - Link adalah Logout (karena ada modal konfirmasi dulu)
            // - Link adalah pemicu Modal (seperti tombol Tambah Data)
            // - Link adalah link kosong '#'
            
            const href = $(this).attr('href');
            const isModal = $(this).attr('data-toggle') === 'modal';
            const isLogout = $(this).hasClass('logout-link');

            if (href && href !== '#' && !isModal && !isLogout) {
                $('#loading-screen').removeClass('hidden');
            }
        });

        // 3. Khusus untuk Tombol Logout (Munculkan loader setelah klik 'Keluar' di Modal)
        $('#konfirmasiKeluar .btn-danger').on('click', function() {
            $('#loading-screen').removeClass('hidden');
        });
        
        // 4. Munculkan loader saat form disubmit (Misal: Tambah Barang/Filter)
        $('form').on('submit', function() {
            // Pastikan form tidak memiliki class 'no-loader' jika ada form khusus
            if (!$(this).hasClass('no-loader')) {
                $('#loading-screen').removeClass('hidden');
            }
        });
    });
});
