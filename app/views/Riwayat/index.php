<?php
if (!isset($_SESSION['login'])) {
    header("Location: " . BASEURL . "Login");
    exit;
}
$id_role = $_SESSION['id_role'];
$isAdmin = in_array($id_role, ['1', '2', '3', '4']);
?>

<style>
    /* Custom Status Badges */
    .badge-status-processing {
        background-color: #facc15 !important; /* Yellow */
        color: #854d0e !important;
    }
    .badge-status-incomplete {
        background-color: #fb923c !important; /* Orange */
        color: #fff !important;
    }
    .badge-status-returned {
        background-color: #0c1740 !important; /* Navy */
        color: #fff !important;
    }
    .badge-status-approved {
        background-color: #22c55e !important; /* Green */
        color: #fff !important;
    }
    .badge-status-rejected {
        background-color: #ef4444 !important; /* Red */
        color: #fff !important;
    }
    
    /* Stat Card Text Refinement */
    .stat-label-black {
        color: #1e293b !important;
        font-weight: 600 !important;
    }
</style>

<div class="content">
    <div class="container-fluid p-4 content-beranda">

        <div class="d-flex align-items-center justify-content-between mb-4">
            <h4 class="mb-0 font-weight-bold text-dark">Riwayat Peminjaman</h4>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-12 col-md-6 col-xl">
                <div class="stat-card bg-white">
                    <div>
                        <div class="stat-label stat-label-black">Total Diterima</div>
                        <div class="stat-value stat-value-navy"><?= isset($data['total_disetujui']) ? $data['total_disetujui'] : 0; ?></div>
                    </div>
                    <div class="stat-icon icon-dark">
                        <i class="fas fa-check"></i>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl">
                <div class="stat-card bg-white">
                    <div>
                        <div class="stat-label stat-label-black">Total Diproses</div>
                        <div class="stat-value stat-value-navy"><?= isset($data['total_diproses']) ? $data['total_diproses'] : 0; ?></div>
                    </div>
                    <div class="stat-icon icon-dark">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl">
                <div class="stat-card bg-white">
                    <div>
                        <div class="stat-label stat-label-black">Melengkapi Surat</div>
                        <div class="stat-value stat-value-navy"><?= isset($data['total_surat']) ? $data['total_surat'] : 0; ?></div>
                    </div>
                    <div class="stat-icon icon-dark">
                        <i class="fas fa-file-signature"></i>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl">
                <div class="stat-card bg-white">
                    <div>
                        <div class="stat-label stat-label-black">Total Ditolak</div>
                        <div class="stat-value stat-value-red"><?= isset($data['total_ditolak']) ? $data['total_ditolak'] : 0; ?></div>
                    </div>
                    <div class="stat-icon icon-dark stat-icon-danger-soft">
                        <i class="fas fa-times"></i>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl">
                <div class="stat-card bg-white">
                    <div>
                        <div class="stat-label stat-label-black">Total Pengembalian</div>
                        <div class="stat-value stat-value-navy"><?= isset($data['total_kembali']) ? $data['total_kembali'] : 0; ?></div>
                    </div>
                    <div class="stat-icon icon-dark">
                        <i class="fas fa-box-open"></i>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($data['is_admin']): ?>
            <div class="card mb-4 border-0 shadow-sm nav-card-container">
                <div class="card-body p-2">
                    <ul class="nav nav-pills nav-fill bg-light rounded p-1 nav-pills-container">
                        <li class="nav-item">
                            <a class="nav-link <?= ($data['active_tab'] == 'all') ? 'active font-weight-bold shadow-sm' : 'text-muted'; ?>"
                                href="<?= BASEURL; ?>Riwayat/index/all"
                                style="<?= ($data['active_tab'] == 'all') ? 'background-color: #0C1740;' : ''; ?>">
                                <i class="fas fa-users mr-2"></i>Riwayat Seluruh User
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($data['active_tab'] == 'me') ? 'active font-weight-bold shadow-sm' : 'text-muted'; ?>"
                                href="<?= BASEURL; ?>Riwayat/index/me"
                                style="<?= ($data['active_tab'] == 'me') ? 'background-color: #0C1740;' : ''; ?>">
                                <i class="fas fa-user-tag mr-2"></i>Riwayat Saya
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        <?php endif; ?>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="tableRiwayat" class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="pl-4 py-3 border-0 col-number">No</th>
                            <th class="py-3 border-0">Nama Peminjam</th>
                            <th class="py-3 border-0">Judul Kegiatan</th>
                            <th class="py-3 border-0">Tgl Pengajuan</th>
                            <th id="th-status" class="text-center py-3 border-0 th-sortable">
                                Status <i class="fas fa-filter ml-1"></i>
                            </th>
                            <th class="text-center py-3 border-0 col-action">Aksi</th>
                        </tr>
                    </thead>
                   <tbody>
                    <?php $no = 1; ?>

                    <?php foreach ($data['riwayat'] as $row): ?>
                        <tr>
                            <td class="pl-4 font-weight-bold text-muted"><?= $no++; ?></td>

                            <td>
                                <h6 class="row-title-text">
                                    <?= ($data['active_tab'] == 'all') ? $row['nama_user'] : 'Saya'; ?>
                                </h6>
                            </td>

                            <td><?= $row['judul_kegiatan']; ?></td>

                            <td><?= date('d M Y', strtotime($row['tanggal_pengajuan'])); ?></td>

                            <td class="text-center">
                                <?php
                                $st = strtolower($row['status']);
                                $badgeClass = 'badge-secondary';

                                if ($st == 'disetujui' || $st == 'diterima') {
                                    $badgeClass = 'badge-status-approved';
                                } elseif (strpos($st, 'tolak') !== false) {
                                    $badgeClass = 'badge-status-rejected';
                                } elseif ($st == 'melengkapi surat') {
                                    $badgeClass = 'badge-status-incomplete';
                                } elseif ($st == 'diproses') {
                                    $badgeClass = 'badge-status-processing';
                                } elseif ($st == 'dikembalikan' || $st == 'selesai') {
                                    $badgeClass = 'badge-status-returned';
                                }
                                ?>

                                <span class="badge <?= $badgeClass; ?> badge-status">
                                    <?= ucfirst($st); ?>
                                </span>
                            </td>

                            <td class="text-center">
                                <?php if ($st == 'melengkapi surat'): ?>

                                    <div class="action-buttons-group">
                                        <a href="<?= BASEURL; ?>TemplateSurat/lengkapi/<?= IdObfuscator::encode($row['id_peminjaman']); ?>"
                                            class="btn-action btn-upload"
                                            data-toggle="tooltip"
                                            title="Upload Bukti Surat">
                                            <i class="fas fa-file-upload"></i>
                                            <span class="btn-text">Upload Surat</span>
                                        </a>

                                        <a href="<?= BASEURL; ?>Peminjaman/tambahBarang/<?= IdObfuscator::encode($row['id_peminjaman']); ?>"
                                            class="btn-action btn-add"
                                            data-toggle="tooltip"
                                            title="Tambah Barang Peminjaman">
                                            <i class="fas fa-plus-circle"></i>
                                            <span class="btn-text">Tambah Barang</span>
                                        </a>
                                    </div>

                                <?php else: ?>

                                    <a href="<?= BASEURL; ?>Riwayat/detail/<?= IdObfuscator::encode($row['id_peminjaman']); ?>"
                                        class="btn-action btn-detail"
                                        data-toggle="tooltip"
                                        title="Lihat Detail Peminjaman">
                                        <i class="fas fa-eye"></i>
                                        <span class="btn-text">Lihat Detail</span>
                                    </a>

                                <?php endif; ?>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $.fn.dataTable.ext.errMode = 'none';
    $.fn.dataTable.ext.errMode = function (settings, helpPage, message) {
        console.log("DataTables Error: ", message);
    };
</script>