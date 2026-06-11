<?php
if (!isset($_SESSION['login'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}

// --- [PERUBAHAN 1: Cek Session Langsung Di Sini] ---
// Ini menjamin status edit terdeteksi, tidak peduli dari Controller mana asalnya
$isEdit = (isset($_SESSION['edit_mode']) && $_SESSION['edit_mode'] === true);
?>

<style>
    .floating-cart-btn {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background-color: #0c1740;
        color: white !important;
        padding: 15px 25px;
        border-radius: 50px;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 10px 25px rgba(12, 23, 64, 0.3);
        text-decoration: none !important;
        z-index: 1000;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 2px solid rgba(255, 255, 255, 0.1);
    }

    .floating-cart-btn:hover {
        transform: translateY(-5px) scale(1.02);
        background-color: #1e3a8a;
        box-shadow: 0 15px 30px rgba(12, 23, 64, 0.4);
    }

    .cart-count-badge {
        background-color: #ef4444;
        color: white;
        font-size: 11px;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 10px;
        position: absolute;
        top: -10px;
        left: -10px;
        border: 2px solid white;
    }

    .floating-cart-btn i {
        font-size: 18px;
    }

    .floating-cart-btn span {
        font-weight: 600;
        font-size: 15px;
    }

    @keyframes pulse-border {
        0% {
            box-shadow: 0 0 0 0 rgba(12, 23, 64, 0.4);
        }

        70% {
            box-shadow: 0 0 0 15px rgba(12, 23, 64, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(12, 23, 64, 0);
        }
    }

    .floating-cart-btn {
        animation: pulse-border 2s infinite;
    }
</style>

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
            <?php if (!empty($data['barang'])): ?>
                <?php foreach ($data['barang'] as $brg): ?>
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
                                <img src="<?= BASEURL; ?>img/<?= $fotoPath; ?>" alt="<?= $brg['sub_barang']; ?>"
                                    onerror="this.onerror=null; this.src='<?= BASEURL; ?>img/foto-barang/default_tools.png';">
                            </div>

                            <div class="card-desc">
                                <h6 class="barang-title">
                                    <?= $brg['sub_barang']; ?>
                                </h6>

                                <a href="<?= BASEURL; ?>Peminjaman/tambahItem/<?= IdObfuscator::encode($brg['id_jenis_barang']); ?>"
                                    class="btn-pinjam-now">
                                    Pinjam
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <img src="<?= BASEURL; ?>img/empty_state.svg" width="200" style="opacity: 0.5;">
                    <h5 class="text-muted mt-3">Barang tidak ditemukan.</h5>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($data['cart_count'] > 0 || $isEdit): ?>
        <a href="<?= BASEURL; ?>Peminjaman/formPeminjaman" class="floating-cart-btn">
            <div class="position-relative">
                <i class="fas fa-shopping-basket"></i>
                <?php if ($data['cart_count'] > 0): ?>
                    <span class="cart-count-badge"><?= $data['cart_count']; ?></span>
                <?php endif; ?>
            </div>
            <span>
                <?= $isEdit ? 'Kembali ke Edit Form' : 'Lanjutkan Pengajuan'; ?>
            </span>
            <i class="fas fa-chevron-right ml-1"></i>
        </a>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // --- UPDATE SCRIPT SWEETALERT (AUTO DETECT EDIT / NEW ITEM) ---
    document.addEventListener("DOMContentLoaded", function () {

        // 1. Ambil Status dari PHP
        const isEditMode = <?= $isEdit ? 'true' : 'false'; ?>;
        const hasItems = <?= ($data['cart_count'] > 0) ? 'true' : 'false'; ?>;

        if (isEditMode || hasItems) {
            // Gunakan event delegation agar menangkap klik di sidebar/header juga
            document.body.addEventListener('click', function (e) {
                const link = e.target.closest('a');
                if (!link) return;

                const targetUrl = link.getAttribute('href');

                // Filter link yang aman
                if (!targetUrl || targetUrl === '#' || targetUrl.startsWith('javascript')) return;
                if (link.hasAttribute('data-toggle') || link.hasAttribute('data-target')) return;
                if (targetUrl.includes('hapusItem')) return;
                if (link.classList.contains('btn-pinjam-now')) return;
                if (targetUrl.includes('formPeminjaman')) return;
                if (targetUrl === '<?= BASEURL; ?>Peminjaman' || targetUrl === '<?= BASEURL; ?>Peminjaman/') return;

                // --- CEGAT NAVIGASI ---
                e.preventDefault();
                e.stopImmediatePropagation();

                let swalTitle, swalText, btnConfirmText, btnDenyText, denyUrl;

                if (isEditMode) {
                    swalTitle = 'Keluar dari Edit Mode?';
                    swalText = 'Perubahan yang belum disimpan akan hilang. Lanjutkan ke form atau batalkan edit?';
                    btnConfirmText = 'Lanjutkan Edit';
                    btnDenyText = 'Batal Edit';
                    denyUrl = '<?= BASEURL; ?>Peminjaman/batalEdit';
                } else {
                    swalTitle = 'Belum Mengajukan Barang!';
                    swalText = 'Anda memiliki barang di daftar. Ingin selesaikan pengajuan Anda atau hapus daftar?';
                    btnConfirmText = 'Selesaikan Pengajuan';
                    btnDenyText = 'Hapus Daftar';
                    denyUrl = '<?= BASEURL; ?>Peminjaman/batal';
                }

                Swal.fire({
                    title: swalTitle,
                    text: swalText,
                    icon: 'warning',
                    width: '700px',
                    showCancelButton: true,
                    showDenyButton: true,
                    showConfirmButton: true,
                    confirmButtonText: btnConfirmText,
                    denyButtonText: btnDenyText,
                    cancelButtonText: 'Kembali',
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'btn-send px-4 py-2',
                        denyButton: 'btn-back px-4 py-2',
                        cancelButton: 'btn-back px-4 py-2',
                        actions: 'gap-2 mt-3'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASEURL; ?>Peminjaman/formPeminjaman';
                    } else if (result.isDenied) {
                        // Reset sesei lewat fetch agar tidak redirect dua kali
                        fetch(denyUrl).then(() => {
                            window.location.href = targetUrl;
                        });
                    }
                });
            }, true); // Use capture phase
        }
    });

    document.getElementById('customSearch').addEventListener('keyup', function () {
        let keyword = this.value;

        // Gunakan Fetch API untuk mengirim keyword ke Controller
        fetch('<?= BASEURL ?>DetailBarang/cari', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'keyword=' + encodeURIComponent(keyword)
        })
            .then(response => response.text())
            .then(html => {
                // Ganti isi tbody dengan hasil pencarian
                document.querySelector('#myTable tbody').innerHTML = html;
            });
    });
</script>