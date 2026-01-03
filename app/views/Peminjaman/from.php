<?php
if (!isset($_SESSION['login'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}
?>

<div class="content">

    <style>
        /* --- Layout & Form --- */
        .form-card {
            background-color: white;
            padding: 40px;
            border-radius: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.02);
            font-family: 'Poppins', sans-serif;
            min-height: 85vh;
            position: relative;
        }

        .form-title {
            color: #0c1740;
            font-weight: 700;
            font-size: 26px;
            margin-bottom: 30px;
        }

        .lbl {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 8px;
            display: block;
        }

        .inp-custom {
            width: 100%;
            background-color: #F2F5F9;
            border: none;
            border-radius: 12px;
            padding: 0 15px;
            font-size: 14px;
            color: #334155;
            height: 50px;
            transition: 0.3s;
        }

        .inp-custom:focus {
            outline: none;
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(12, 23, 64, 0.1);
        }

        .inp-readonly {
            background-color: #e9ecef !important;
            color: #495057;
            font-weight: 600;
            cursor: default;
        }

        .gap-row {
            margin-bottom: 20px;
        }

        .icon-wrap {
            position: relative;
            width: 100%;
        }

        .icon-inside {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            font-size: 16px;
        }

        .row-item-grid {
            margin-left: -10px;
            margin-right: -10px;
        }

        .row-item-grid>[class*='col-'] {
            padding-left: 10px;
            padding-right: 10px;
        }

        .item-row {
            margin-bottom: 25px;
            animation: fadeIn 0.5s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* --- Tombol Hapus Kecil --- */
        .btn-cancel-item {
            color: #ef4444;
            font-size: 12px;
            font-weight: 600;
            background-color: #fee2e2;
            padding: 4px 12px;
            border-radius: 15px;
            transition: 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            border: none;
            cursor: pointer;
        }

        .btn-cancel-item:hover {
            background-color: #ef4444;
            color: white;
            text-decoration: none;
        }

        /* --- Tombol Tambah (+) --- */
        .add-more-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            margin-bottom: 40px;
            border-top: 1px dashed #e2e8f0;
            padding-top: 20px;
        }

        .btn-add-more {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            width: 45px;
            height: 45px;
            background-color: #0c1740;
            color: white;
            border-radius: 50%;
            border: none;
            cursor: pointer;
            transition: 0.2s;
            text-decoration: none !important;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            font-size: 18px;
        }

        .btn-add-more:hover {
            transform: scale(1.1);
            background-color: #1e3a8a;
            color: white;
        }

        /* --- Footer --- */
        .action-footer {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            padding-top: 10px;
        }

        .btn-back {
            background-color: #e2e8f0;
            color: #475569;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
        }

        .btn-back:hover {
            background-color: #cbd5e1;
            color: #1e293b;
            text-decoration: none;
        }

        .btn-send {
            background-color: #0c1740;
            color: white;
            padding: 12px 50px;
            border-radius: 10px;
            border: none;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-send:hover {
            background-color: #1e3a8a;
            transform: translateY(-2px);
        }

        /* --- MODAL CSS (PENTING!) --- */
        .custom-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(12, 23, 64, 0.8);
            /* Overlay gelap */
            z-index: 99999;
            /* Pastikan di atas Sidebar */
            display: none;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(5px);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .custom-modal-overlay.show {
            display: flex;
            opacity: 1;
        }

        .custom-modal-box {
            background: white;
            width: 400px;
            padding: 40px 30px;
            border-radius: 25px;
            text-align: center;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
            transform: scale(0.8);
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .custom-modal-overlay.show .custom-modal-box {
            transform: scale(1);
        }

        .modal-icon-circle {
            width: 80px;
            height: 80px;
            background-color: #fee2e2;
            color: #ef4444;
            border-radius: 50%;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            font-size: 35px;
            margin: 0 auto 20px auto;
        }

        .modal-title {
            font-size: 22px;
            font-weight: 700;
            color: #0c1740;
            margin-bottom: 10px;
        }

        .modal-desc {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 30px;
            line-height: 1.5;
        }

        .modal-btn-group {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .btn-modal-cancel {
            background: #f1f5f9;
            color: #475569;
            padding: 12px 25px;
            border-radius: 10px;
            font-weight: 600;
            border: none;
            cursor: pointer;
        }

        .btn-modal-delete {
            background: #ef4444;
            color: white;
            padding: 12px 25px;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            display: inline-block;
        }

        .btn-modal-delete:hover {
            background: #dc2626;
            color: white;
            text-decoration: none;
        }

        /* Robot */
        .right-section {
            display: flex;
            justify-content: center;
            padding-top: 10px;
        }

        .content-figure {
            position: relative;
        }

        .hello-text {
            position: absolute;
            top: 10%;
            right: -10%;
            background: rgba(255, 255, 255, 0.9);
            padding: 5px 15px;
            border-radius: 15px;
            font-weight: bold;
            color: #0c1740;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 768px) {
            .right-section {
                display: none;
            }
        }
    </style>

    <div class="container-fluid">
        <div class="form-card">
            <form action="<?= BASEURL ?>Peminjaman/prosesTambahPeminjaman" method="post">
                <div class="row">
                    <div class="col-md-7">
                        <h2 class="form-title">Peminjaman</h2>
                        <div class="gap-row">
                            <label class="lbl">Judul kegiatan</label>
                            <input type="text" name="judul_kegiatan" class="inp-custom" required>
                        </div>
                        <div class="gap-row">
                            <label class="lbl">Tanggal pengajuan</label>
                            <div class="icon-wrap">
                                <input type="date" name="tanggal_pengajuan" class="inp-custom" value="<?= date('Y-m-d'); ?>" required>
                                <i class="fa-regular fa-calendar icon-inside" style="color: #1e293b;"></i>
                            </div>
                        </div>
                        <div class="row row-item-grid gap-row">
                            <div class="col-md-6">
                                <label class="lbl">Mulai dari tanggal</label>
                                <div class="icon-wrap">
                                    <input type="date" name="tanggal_peminjaman" class="inp-custom" required>
                                    <i class="fa-regular fa-calendar icon-inside" style="color: #1e293b;"></i>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="lbl">Sampai tanggal</label>
                                <div class="icon-wrap">
                                    <input type="date" name="tanggal_pengembalian" class="inp-custom" required>
                                    <i class="fa-regular fa-calendar icon-inside" style="color: #1e293b;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 right-section">
                        <div class="content-figure">
                            <img id="img-figure-daftar" src="<?= BASEURL ?>img/happy robot assistant.svg" alt="figure" />
                            <div class="hello-text">Hello! 👋</div>
                        </div>
                    </div>
                </div>

                <hr style="border-top: 2px dashed #e2e8f0; margin: 30px 0;">

                <div class="row">
                    <div class="col-12">
                        <?php if (!empty($data['barang_selected'])): ?>

                            <?php foreach ($data['barang_selected'] as $item): ?>
                                <div class="item-row">
                                    <div class="row row-item-grid align-items-end">
                                        <div class="col-md-5">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <label class="lbl mb-0">Jenis Barang</label>
                                                <button type="button" class="btn-cancel-item"
                                                    onclick="konfirmasiHapus('<?= BASEURL; ?>Peminjaman/hapusItem/<?= $item['id_jenis_barang']; ?>')">
                                                    <i class="fa-solid fa-circle-xmark"></i> Hapus
                                                </button>
                                            </div>
                                            <div class="icon-wrap">
                                                <input type="hidden" name="id_jenis_barang[]" value="<?= $item['id_jenis_barang']; ?>">
                                                <input type="text" class="inp-custom inp-readonly" value="<?= $item['sub_barang']; ?>" readonly>
                                                <i class="fa-solid fa-check icon-inside" style="color: #22c55e; font-size: 18px;"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="lbl">Jumlah</label>
                                            <input type="number" name="jumlah_peminjaman[]" class="inp-custom" min="1" value="1" required style="text-align: center;">
                                        </div>
                                        <div class="col-md-5">
                                            <label class="lbl">Keterangan</label>
                                            <input type="text" name="keterangan_peminjaman[]" class="inp-custom" placeholder="-">
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <div class="add-more-container">
                                <a href="<?= BASEURL; ?>Peminjaman" class="btn-add-more" title="Tambah Barang Lain">
                                    <i class="fa-solid fa-plus"></i>
                                </a>
                            </div>

                        <?php else: ?>
                            <div class="alert alert-warning">
                                Keranjang kosong. Silakan pilih barang di menu utama.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="action-footer">
                    <a href="<?= BASEURL; ?>Peminjaman" class="btn-back">Kembali</a>
                    <?php if (!empty($data['barang_selected'])): ?>
                        <button type="submit" class="btn-send">Kirim</button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modalHapus" class="custom-modal-overlay">
    <div class="custom-modal-box">
        <div class="modal-icon-circle">
            <i class="fa-regular fa-trash-can"></i>
        </div>
        <h3 class="modal-title">Hapus Item?</h3>
        <p class="modal-desc">
            Apakah Anda yakin ingin menghapus barang ini<br>dari daftar peminjaman?
        </p>
        <div class="modal-btn-group">
            <button type="button" onclick="tutupModal()" class="btn-modal-cancel">Batal</button>
            <a id="btnLinkHapus" href="#" class="btn-modal-delete">Ya, Hapus</a>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const modal = document.getElementById('modalHapus');
        const btnLinkHapus = document.getElementById('btnLinkHapus');

        window.konfirmasiHapus = function(url) {
            if (modal && btnLinkHapus) {
                btnLinkHapus.setAttribute('href', url);
                modal.classList.add('show');
            }
        };

        window.tutupModal = function() {
            if (modal) {
                modal.classList.remove('show');
            }
        };

        window.onclick = function(event) {
            if (event.target == modal) {
                tutupModal();
            }
        };
    });
</script>