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
    document.addEventListener("DOMContentLoaded", function() {
        
        // Ambil nilai dari variabel PHP $isEdit yang kita buat di baris atas tadi
        const isEditMode = <?= json_encode($isEdit); ?>;
        
        console.log("Status Edit Mode:", isEditMode); // Cek console browser Anda

        if (isEditMode === true) {
            
            // Gunakan Event Delegation pada BODY agar menangkap klik di Sidebar juga
            document.body.addEventListener('click', function(e) {
                
                // Cari elemen <a> (link) terdekat dari yang diklik
                const link = e.target.closest('a');

                // Jika bukan link, abaikan
                if (!link) return;

                const targetUrl = link.getAttribute('href');

                // A. WHITELIST: Abaikan link kosong/hash/javascript
                if (!targetUrl || targetUrl === '#' || targetUrl.startsWith('javascript')) return;

                // B. WHITELIST: Tombol "Pinjam" (PENTING: Biarkan user menambah barang)
                // Kita cek class 'btn-pinjam-now' yang ada di tombol pinjam
                if (link.classList.contains('btn-pinjam-now')) return;

                // C. WHITELIST: Tombol Logout / Modal (PENTING: Biarkan modal muncul)
                if (link.hasAttribute('data-toggle') || link.hasAttribute('data-target')) return;
                
                // D. WHITELIST: Link ke Form Peminjaman sendiri
                if (targetUrl.includes('Peminjaman/formPeminjaman')) return;

                // --- BLOKIR NAVIGASI & TAMPILKAN POPUP ---
                e.preventDefault();
                e.stopImmediatePropagation(); // Hentikan script lain (seperti loader)

                Swal.fire({
                    title: 'Batal Memilih Barang?',
                    text: "Anda sedang dalam mode Edit/Tambah barang. Keluar sekarang akan membatalkan proses pemilihan.",
                    icon: 'warning',
                    showCancelButton: true,
                    showDenyButton: true,
                    confirmButtonColor: '#d33',     // Merah (Keluar)
                    denyButtonColor: '#0d1b3e',     // Navy (Kembali ke Form)
                    cancelButtonColor: '#6e7881',   // Abu (Tetap Disini)
                    confirmButtonText: 'Keluar & Batal Edit',
                    denyButtonText: 'Kembali ke Form',
                    cancelButtonText: 'Tetap Disini'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // OPSI 1: KELUAR & HAPUS SESI EDIT
                        window.location.href = '<?= BASEURL; ?>Peminjaman/batalEdit';
                        
                    } else if (result.isDenied) {
                        // OPSI 2: BALIK KE FORM (Tanpa nambah barang)
                        window.location.href = '<?= BASEURL; ?>Peminjaman/formPeminjaman';
                        
                    } else {
                        // OPSI 3: DIAM (Tutup popup)
                    }
                });
            }, true); // 'true' = Capture Phase (Prioritas Tinggi)
        }
    });
</script>