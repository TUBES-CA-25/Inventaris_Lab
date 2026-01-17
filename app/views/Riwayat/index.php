<?php
if (!isset($_SESSION['login'])) {
    header("Location: " . BASEURL . "Login");
    exit;
}
$id_role = $_SESSION['id_role'];
$isAdmin = in_array($id_role, ['1', '2', '3', '4']);
?>

<div class="content">
    <div class="container-fluid p-4">
    
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-0 font-weight-bold text-dark">Riwayat Peminjaman</h4>
            <small class="text-muted">Pantau status dan histori peminjaman barang.</small>
        </div>
        
        </div>

    <?php if ($data['is_admin']) : ?>
    <div class="card mb-4 border-0 shadow-sm" style="border-radius: 10px;">
        <div class="card-body p-2">
            <ul class="nav nav-pills nav-fill bg-light rounded p-1" style="border: 1px solid #eee;">
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

    <div class="card card-modern shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="pl-4 py-3 border-0" width="5%">No</th>
                            
                            <th class="py-3 border-0">
                                <?= ($data['active_tab'] == 'all') ? 'Nama Peminjam' : 'Judul Kegiatan'; ?>
                            </th>

                            <?php if ($data['active_tab'] == 'all') : ?>
                                <th class="py-3 border-0">Kegiatan</th>
                            <?php endif; ?>

                            <th class="py-3 border-0">Tgl Pengajuan</th>
                            <th class="text-center py-3 border-0">Status</th>
                            
                            <th class="text-center py-3 border-0" width="<?= ($data['active_tab'] == 'all') ? '10%' : '15%'; ?>">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php if (empty($data['riwayat'])) : ?>
                            <tr>
                                <td colspan="<?= ($data['active_tab'] == 'all') ? '6' : '5'; ?>" class="text-center py-5 text-muted">
                                    <img src="<?= BASEURL; ?>img/empty.svg" alt="Kosong" style="width: 100px; opacity: 0.5;" class="mb-3">
                                    <p class="mb-0">Belum ada riwayat peminjaman.</p>
                                </td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($data['riwayat'] as $row) : ?>
                                <tr>
                                    <td class="pl-4 font-weight-bold text-muted"><?= $no++; ?></td>
                                    
                                    <td>
                                        <?php if ($data['active_tab'] == 'all') : ?>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle bg-navy-light text-navy mr-3 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; border-radius: 50%; font-weight: bold; font-size: 14px;">
                                                    <?= substr($row['nama_peminjam'], 0, 1); ?>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 text-dark font-weight-bold" style="font-size: 14px;"><?= $row['nama_peminjam']; ?></h6>
                                                </div>
                                            </div>
                                        <?php else : ?>
                                            <div class="d-flex align-items-center">
                                                <div class="icon-circle bg-light text-muted mr-3 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; border-radius: 8px;">
                                                    <i class="fas fa-clipboard-list"></i>
                                                </div>
                                                <h6 class="mb-0 text-dark font-weight-bold" style="font-size: 14px;"><?= $row['judul_kegiatan']; ?></h6>
                                            </div>
                                        <?php endif; ?>
                                    </td>

                                    <?php if ($data['active_tab'] == 'all') : ?>
                                        <td><?= $row['judul_kegiatan']; ?></td>
                                    <?php endif; ?>

                                    <td><?= date('d M Y', strtotime($row['tanggal_pengajuan'])); ?></td>
                                    
                                    <td class="text-center">
                                        <?php 
                                            $st = strtolower($row['status']);
                                            $badgeColor = 'secondary';
                                            $iconStatus = '';

                                            if($st == 'disetujui' || $st == 'diterima') {
                                                $badgeColor = 'success'; $iconStatus = 'fa-check';
                                            } elseif($st == 'ditolak') {
                                                $badgeColor = 'danger'; $iconStatus = 'fa-times';
                                            } elseif($st == 'melengkapi surat') {
                                                $badgeColor = 'warning'; $iconStatus = 'fa-file-signature';
                                            } elseif($st == 'diproses') {
                                                $badgeColor = 'info'; $iconStatus = 'fa-spinner';
                                            }
                                        ?>
                                        <span class="badge badge-<?= $badgeColor; ?> px-3 py-2 rounded-pill">
                                            <i class="fas <?= $iconStatus; ?> mr-1" style="font-size: 10px;"></i><?= ucfirst($st); ?>
                                        </span>
                                    </td>
                                    
                                    <td class="text-center">
                                        <?php if ($st == 'melengkapi surat') : ?>
                                        <div class="action-buttons-group">
                                            <a href="<?= BASEURL; ?>TemplateSurat/lengkapi/<?= $row['id_peminjaman']; ?>" 
                                               class="btn-action btn-upload"
                                               data-toggle="tooltip" 
                                               data-placement="top"
                                               title="Upload Bukti Surat">
                                                <i class="fas fa-file-upload"></i>
                                                <span class="btn-text">Upload Surat</span>
                                            </a>
                                            
                                            <a href="<?= BASEURL; ?>Peminjaman/tambahBarang/<?= $row['id_peminjaman']; ?>" 
                                               class="btn-action btn-add"
                                               data-toggle="tooltip" 
                                               data-placement="top"
                                               title="Tambah Barang Peminjaman">
                                                <i class="fas fa-plus-circle"></i>
                                                <span class="btn-text">Tambah Barang</span>
                                            </a>
                                        </div>

                                    <?php else : ?>
                                        <a href="<?= BASEURL; ?>Riwayat/detail/<?= $row['id_peminjaman']; ?>" 
                                           class="btn-action btn-detail"
                                           data-toggle="tooltip" 
                                           data-placement="top"
                                           title="Lihat Detail Peminjaman">
                                            <i class="fas fa-eye"></i>
                                            <span class="btn-text">Lihat Detail</span>
                                        </a>
                                    <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>