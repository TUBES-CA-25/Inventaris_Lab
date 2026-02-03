const formPeminjaman = document.getElementById('formPeminjaman');

    if (formPeminjaman) {
        formPeminjaman.addEventListener('submit', function(e) {
            var btn = document.getElementById('btnSubmitPeminjaman');
            if (btn) {
                // Ubah teks tombol jadi loading
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
                btn.style.opacity = '0.7';
                btn.style.pointerEvents = 'none'; // Cegah klik ganda
            }
        });
    }

    // Cek apakah ada data Flash dari Controller
    <?php if (isset($_SESSION['flash'])) : ?>
        Swal.fire({
            title: "<?= $_SESSION['flash']['pesan']; ?>",
            html: "<?= $_SESSION['flash']['aksi']; ?>", 
            icon: "<?= $_SESSION['flash']['tipe']; ?>",
            confirmButtonColor: '#1250ba',
            confirmButtonText: 'Oke, Saya Cek Lagi'
        });
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>
    
    function konfirmasiHapus(url) {
        document.getElementById('btnLinkHapus').href = url;
        document.getElementById('modalHapus').style.display = 'flex';
    }

    function tutupModal() {
        document.getElementById('modalHapus').style.display = 'none';
    }

    // --- FITUR INTERCEPT NAVIGASI MODE EDIT ---
    document.addEventListener("DOMContentLoaded", function() {
        // Ambil status edit dari PHP
        const isEditMode = <?= $isEdit ? 'true' : 'false'; ?>;
        
        if (isEditMode) {
            // Seleksi semua link, termasuk yang ada di SIDEBAR
            const links = document.querySelectorAll('a');
            const form = document.getElementById('formPeminjaman');

            links.forEach(link => {
                // Gunakan 'click' dengan option capture false (default)
                link.addEventListener('click', function(e) {
                    const targetUrl = this.getAttribute('href');

                    // 1. Abaikan link kosong, hash (#), javascript, atau link modal (seperti Logout di sidebar)
                    if (!targetUrl || targetUrl === '#' || targetUrl.startsWith('javascript')) return;
                    if (this.hasAttribute('data-toggle') || this.hasAttribute('data-target')) return;

                    // 2. Abaikan tombol hapus item (di dalam form)
                    if (this.id === 'btnLinkHapus' || this.classList.contains('btn-modal-delete')) return;
                    if (targetUrl.includes('hapusItem')) return;

                    // 3. PENGECUALIAN: Izinkan tombol "Tambah Barang" (Ke Katalog)
                    if (this.classList.contains('btn-safe-action')) return;
                    if (targetUrl === '<?= BASEURL; ?>Peminjaman' || targetUrl === '<?= BASEURL; ?>Peminjaman/') return;

                    // --- CEGAH NAVIGASI & LOADER ---
                    
                    // Stop browser pindah halaman
                    e.preventDefault(); 
                    
                    // KUNCI UTAMA: Stop event ini agar tidak terdeteksi oleh script LOADER global
                    e.stopImmediatePropagation(); 

                    Swal.fire({
                        title: 'Keluar dari Edit Mode?',
                        text: "Anda sedang dalam mode edit. Perubahan yang belum disimpan akan hilang.",
                        icon: 'question',
                        showCancelButton: true,
                        showDenyButton: true,
                        confirmButtonColor: '#3085d6',
                        denyButtonColor: '#d33',
                        cancelButtonColor: '#6e7881',
                        confirmButtonText: 'Simpan Perubahan',
                        denyButtonText: 'Batal Edit',
                        cancelButtonText: 'Kembali'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // OPSI 1: SIMPAN
                            if(form) form.submit();
                        } else if (result.isDenied) {
                            // OPSI 2: BATAL EDIT (Keluar paksa)
                            window.location.href = '<?= BASEURL; ?>Peminjaman/batalEdit';
                        } else {
                            // OPSI 3: KEMBALI (Tetap di halaman)
                            // Tidak melakukan apa-apa
                        }
                    });
                });
            });
        }
    });