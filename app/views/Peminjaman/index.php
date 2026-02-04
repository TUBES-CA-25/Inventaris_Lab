<?php
if (!isset($_SESSION['login'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}

// --- [PERUBAHAN 1: Cek Session Langsung Di Sini] ---
// Ini menjamin status edit terdeteksi, tidak peduli dari Controller mana asalnya
$isEdit = (isset($_SESSION['edit_mode']) && $_SESSION['edit_mode'] === true);
?>

<div class="content">
    <div class="container-fluid content-beranda p-4">

        <div class="header-section mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h3 class="page-title">Barang Laboratorium</h3>

            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="customSearch" placeholder="Search...">
            </div>
        </div>

        <div class="row">
            <?php if (!empty($data['barang'])) : ?>
                <?php foreach ($data['barang'] as $brg) : ?>
                    <?php
                    $fotoPath = 'default_tools.png';

                    if (!empty($brg['foto_barang'])) {
                        $cleanPath = str_replace('../public/img/', '', $brg['foto_barang']);
                        $fotoPath = $cleanPath;
                    }
                    ?>

                    <div class="col-12 col-sm-6 col-md-4 col-xl-3 mb-4">
                        <div class="card-item">
                            <div class="card-img-container">
                                <img src="<?= BASEURL; ?>img/<?= $fotoPath; ?>"
                                    alt="<?= $brg['sub_barang']; ?>"
                                    onerror="this.onerror=null; this.src='<?= BASEURL; ?>img/foto-barang/default_tools.png';">
                            </div>

                            <div class="card-desc">
                                <h6 class="barang-title"><?= $brg['sub_barang']; ?></h6>
                                
                                <a href="<?= BASEURL; ?>Peminjaman/tambahItem/<?= IdObfuscator::encode($brg['id_jenis_barang']); ?>"
                                    class="btn-pinjam-now">
                                    Pinjam
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="col-12 text-center py-5">
                    <img src="<?= BASEURL; ?>img/empty_state.svg" width="200" style="opacity: 0.5;">
                    <h5 class="text-muted mt-3">Barang tidak ditemukan.</h5>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // --- UPDATE SCRIPT SWEETALERT (AUTO DETECT EDIT / NEW ITEM) ---
    document.addEventListener("DOMContentLoaded", function() {
        
        // 1. Ambil Status dari PHP
        const isEditMode = <?= $isEdit ? 'true' : 'false'; ?>;
        // Cek apakah array barang_selected tidak kosong
        const hasItems   = <?= !empty($data['barang_selected']) ? 'true' : 'false'; ?>;
        
        // JALANKAN LOGIKA JIKA: (Sedang Edit) ATAU (Peminjaman Baru & Ada Barang)
        if (isEditMode || hasItems) {
            
            const links = document.querySelectorAll('a');
            const form = document.getElementById('formPeminjaman');

            links.forEach(link => {
                link.addEventListener('click', function(e) {
                    const targetUrl = this.getAttribute('href');

                    // --- FILTER LINK AMAN (YANG TIDAK PERLU DICEGAT) ---
                    if (!targetUrl || targetUrl === '#' || targetUrl.startsWith('javascript')) return;
                    if (this.hasAttribute('data-toggle') || this.hasAttribute('data-target')) return; // Modal Logout
                    if (this.id === 'btnLinkHapus' || this.classList.contains('btn-modal-delete')) return; // Hapus Item
                    if (targetUrl.includes('hapusItem')) return; // Aksi Hapus Item PHP
                    if (this.classList.contains('btn-safe-action')) return; // Tombol Tambah Barang
                    if (targetUrl === '<?= BASEURL; ?>Peminjaman' || targetUrl === '<?= BASEURL; ?>Peminjaman/') return; // Balik ke Katalog

                    // --- CEGAT NAVIGASI ---
                    e.preventDefault(); 
                    e.stopImmediatePropagation(); 

                    // 2. TENTUKAN KONTEN SWEETALERT BERDASARKAN KONDISI
                    let swalTitle, swalText, btnConfirmText, btnDenyText, denyUrl;

                    if (isEditMode) {
                        // KONDISI A: SEDANG EDIT
                        swalTitle      = 'Keluar dari Edit Mode?';
                        swalText       = 'Perubahan yang belum disimpan akan hilang.';
                        
                        btnConfirmText = 'Simpan Perubahan'; // Tombol Navy
                        btnDenyText    = 'Batal Edit';       // Tombol Putih
                        denyUrl        = '<?= BASEURL; ?>Peminjaman/batalEdit'; // Reset Edit

                    } else {
                        // KONDISI B: PEMINJAMAN BARU (ADA BARANG)
                        swalTitle      = 'Batalkan Peminjaman?';
                        swalText       = 'Anda sudah memilih barang. Keluar sekarang akan menghapus daftar barang.';
                        
                        btnConfirmText = 'Ajukan Sekarang';  // Tombol Navy
                        btnDenyText    = 'Hapus Daftar';     // Tombol Putih
                        denyUrl        = '<?= BASEURL; ?>Peminjaman/batal'; // Reset Keranjang (Fungsi baru di Langkah 1)
                    }

                    // 3. TAMPILKAN SWEETALERT CUSTOM
                    Swal.fire({
                        title: swalTitle,
                        text: swalText,
                        icon: 'warning',
                        
                        showCancelButton: true,
                        showDenyButton: true,
                        showConfirmButton: true,
                        
                        confirmButtonText: btnConfirmText,
                        denyButtonText: btnDenyText,
                        cancelButtonText: 'Kembali', // Tetap di halaman (X)

                        // Matikan Style Bawaan
                        buttonsStyling: false,
                        
                        // CLASS CSS (Menggunakan class tombol asli Anda)
                        customClass: {
                            confirmButton: 'btn-send', // Navy (Class tombol Submit Anda)
                            denyButton: 'btn-back',    // Putih (Class tombol Kembali Anda)
                            cancelButton: 'btn btn-secondary', 
                            actions: 'gap-2'           // Jarak antar tombol (Bootstrap 5)
                        }

                    }).then((result) => {
                        if (result.isConfirmed) {
                            // TOMBOL NAVY -> KIRIM FORM
                            if(form) form.submit();
                        } else if (result.isDenied) {
                            // TOMBOL PUTIH -> RESET / BATALKAN
                            window.location.href = denyUrl;
                        } else {
                            // TOMBOL CANCEL -> DIAM
                        }
                    });
                });
            });
        }
    });
</script>