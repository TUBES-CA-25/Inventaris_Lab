<?php
if (!isset($_SESSION['login'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}

// Helper untuk format tanggal Indonesia
function formatTanggalIndo($tanggal)
{
    if ($tanggal == '0000-00-00' || $tanggal == null) return '-';
    $bulan = array(
        1 =>   'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );
    $pecahkan = explode('-', $tanggal);
    // Format: 20 Januari 2026
    return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
}
?>

<style>
    :root {
        --primary-dark: #0c1740;
    }

    .bg-primary-dark {
        background-color: var(--primary-dark) !important;
        color: white;
    }

    .text-primary-dark {
        color: var(--primary-dark) !important;
    }

    .btn-primary-dark {
        background-color: var(--primary-dark);
        color: white;
        border: 1px solid var(--primary-dark);
    }

    .btn-primary-dark:hover {
        background-color: #0a1233;
        /* Sedikit lebih gelap saat hover */
        color: white;
    }

    .btn-outline-dark-custom {
        color: var(--primary-dark);
        border: 1px solid var(--primary-dark);
        background: transparent;
    }

    .btn-outline-dark-custom:hover {
        background-color: var(--primary-dark);
        color: white;
    }

    .card-custom {
        border-radius: 12px;
        border: none;
        overflow: hidden;
        /* Agar header rounded ikut terpotong */
    }
</style>
<div class="content">
    <div class="container-fluid content-beranda p-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold text-primary-dark mb-1">Detail Pengembalian</h4>
                <small class="text-muted">Informasi lengkap peminjaman dan status barang.</small>
            </div>
            <a href="<?= BASEURL; ?>Pengembalian" class="btn btn-outline-dark-custom shadow-sm px-4">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>

        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="card card-custom shadow-sm h-100">
                    <div class="card-header bg-primary-dark py-3 px-4">
                        <h6 class="m-0 fw-bold"><i class="fas fa-file-alt me-2"></i>Data Peminjaman</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label class="text-muted small fw-bold text-uppercase">Judul Kegiatan</label>
                            <div class="fs-5 text-dark fw-bold"><?= $data['detail']['judul_kegiatan']; ?></div>
                            <div class="text-muted small">Diajukan pada: <?= formatTanggalIndo($data['detail']['tanggal_pengajuan']); ?></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="p-3 rounded" style="background-color: #f8f9fc; border-left: 4px solid #0c1740;">
                                    <small class="text-muted d-block">Tanggal Mulai</small>
                                    <strong class="text-primary-dark"><?= formatTanggalIndo($data['detail']['tanggal_peminjaman']); ?></strong>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="p-3 rounded" style="background-color: #f8f9fc; border-left: 4px solid #dc3545;">
                                    <small class="text-muted d-block">Batas Pengembalian</small>
                                    <strong class="text-danger"><?= formatTanggalIndo($data['detail']['tanggal_pengembalian']); ?></strong>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h6 class="fw-bold text-primary-dark mb-3">Barang yang Dipinjam</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="bg-primary-dark text-white">
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th class="text-center" width="100">Jumlah</th>
                                        <th>Keterangan Awal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-bold"><?= $data['detail']['nama_barang'] ?? '-'; ?></td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary"><?= $data['detail']['jumlah'] ?? '0'; ?> Unit</span>
                                        </td>
                                        <td class="small"><?= $data['detail']['keterangan'] ?? '-'; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="card card-custom shadow-sm h-100">
                    <div class="card-header bg-primary-dark py-3 px-4">
                        <h6 class="m-0 fw-bold"><i class="fas fa-tasks me-2"></i>Status Pengembalian</h6>
                    </div>
                    <div class="card-body p-4 d-flex flex-column">

                        <?php if (!empty($data['detail']['tgl_kembali'])) : ?>
                            <div class="text-center mb-4">
                                <div class="d-inline-flex align-items-center justify-content-center bg-success text-white rounded-circle mb-3" style="width: 60px; height: 60px;">
                                    <i class="fas fa-check fa-2x"></i>
                                </div>
                                <h5 class="fw-bold text-success">Selesai</h5>
                                <p class="text-muted small">Barang telah dikembalikan.</p>
                            </div>

                            <ul class="list-group list-group-flush mb-3">
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span class="text-muted">Tanggal Kembali</span>
                                    <span class="fw-bold text-dark"><?= formatTanggalIndo($data['detail']['tgl_kembali']); ?></span>
                                </li>
                                <li class="list-group-item px-0">
                                    <span class="text-muted d-block mb-1">Keterangan Kondisi</span>
                                    <span class="badge bg-light text-dark border p-2 w-100 text-start fw-normal">
                                        <?= $data['detail']['keterangan_kembali'] ?? '-'; ?>
                                    </span>
                                </li>
                                <li class="list-group-item px-0">
                                    <span class="text-muted d-block mb-1">Detail Masalah</span>
                                    <div class="alert alert-warning py-2 small m-0 border-0" style="background-color: #fff3cd; color: #856404;">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        <?= $data['detail']['detail_masalah'] ?? 'Tidak ada masalah'; ?>
                                    </div>
                                </li>
                            </ul>

                            <div class="mt-auto pt-3 border-top">
                                <small class="text-muted d-block">Petugas Validator:</small>
                                <div class="d-flex align-items-center mt-2">
                                    <div class="bg-primary-dark text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                        <i class="fas fa-user-shield"></i>
                                    </div>
                                    <span class="fw-bold text-dark">
                                        <?= $data['detail']['nama_petugas'] ?? 'ID: ' . ($data['detail']['id_user'] ?? '-'); ?>
                                    </span>
                                </div>
                            </div>

                        <?php else : ?>
                            <div class="text-center my-auto">
                                <img src="<?= BASEURL; ?>img/illustration_pending.svg" alt="" style="width: 100px; opacity: 0.1;" class="mb-3">
                                <h5 class="fw-bold text-primary-dark">Menunggu Pengembalian</h5>
                                <p class="text-muted small mb-4">Data pengembalian belum diinput oleh petugas.</p>

                                <a href="<?= BASEURL; ?>Pengembalian/input/<?= $data['detail']['id_peminjaman']; ?>" class="btn btn-primary-dark w-100 py-2 shadow-sm">
                                    <i class="fas fa-edit me-2"></i>Input Pengembalian
                                </a>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>