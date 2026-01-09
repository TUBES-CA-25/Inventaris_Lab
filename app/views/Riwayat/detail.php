<?php
if (!isset($_SESSION['login'])) {
    header("Location: " . BASEURL . "Login");
    exit;
}
?>
<div class="container-fluid p-4">
    
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-0 font-weight-bold text-dark">Detail Peminjaman</h4>
            <small class="text-muted">Kelola dan pantau detail item yang dipinjam.</small>
        </div>
        <a href="<?= BASEURL; ?>Riwayat/index" class="btn btn-outline-navy shadow-sm">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <div class="card card-modern mb-4">
        <div class="card-header-custom">
            <?php 
                $st = strtolower($data['info_peminjaman']['status']);
                if ($st == 'disetujui' || $st == 'diterima') { 
                    $icon = 'fa-check-circle'; $color = 'text-success'; $bg = 'bg-success-light';
                } elseif ($st == 'ditolak') { 
                    $icon = 'fa-times-circle'; $color = 'text-danger'; $bg = 'bg-danger-light';
                } elseif ($st == 'melengkapi surat') {
                    $icon = 'fa-file-signature'; $color = 'text-warning'; $bg = 'bg-warning-light';
                } else { 
                    $icon = 'fa-hourglass-half'; $color = 'text-info'; $bg = 'bg-info-light';
                }
            ?>
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="status-icon-circle <?= $bg; ?> mr-3">
                        <i class="fas <?= $icon; ?> <?= $color; ?>"></i>
                    </div>
                    <div>
                        <h5 class="mb-1 font-weight-bold">Peminjaman #<?= $data['info_peminjaman']['id_peminjaman']; ?></h5>
                        <p class="mb-0 text-muted small">ID Transaksi</p>
                    </div>
                </div>
                <span class="badge badge-pill badge-status px-4 py-2 <?= 'badge-'.$st; ?>">
                    <i class="fas <?= $icon; ?> mr-1"></i><?= ucfirst($st); ?>
                </span>
            </div>
        </div>

        <div class="card-body p-4">
            <div class="row">
                <div class="col-md-12 mb-4">
                    <div class="info-box-full">
                        <div class="info-icon bg-primary-light">
                            <i class="fas fa-clipboard-list text-primary"></i>
                        </div>
                        <div class="info-content">
                            <label class="info-label">Judul Kegiatan</label>
                            <h6 class="info-value mb-0"><?= $data['info_peminjaman']['judul_kegiatan']; ?></h6>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="info-box">
                        <div class="info-icon bg-info-light">
                            <i class="fas fa-user text-info"></i>
                        </div>
                        <div class="info-content">
                            <label class="info-label">Nama Peminjam</label>
                            <h6 class="info-value mb-0"><?= $data['info_peminjaman']['nama_peminjam']; ?></h6>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="info-box">
                        <div class="info-icon bg-warning-light">
                            <i class="fas fa-calendar-plus text-warning"></i>
                        </div>
                        <div class="info-content">
                            <label class="info-label">Tanggal Pengajuan</label>
                            <h6 class="info-value mb-0"><?= date('d M Y', strtotime($data['info_peminjaman']['tanggal_pengajuan'])); ?></h6>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="info-box">
                        <div class="info-icon bg-success-light">
                            <i class="fas fa-calendar-check text-success"></i>
                        </div>
                        <div class="info-content">
                            <label class="info-label">Tanggal Peminjaman</label>
                            <h6 class="info-value mb-0"><?= date('d M Y', strtotime($data['info_peminjaman']['tanggal_peminjaman'])); ?></h6>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="info-box">
                        <div class="info-icon bg-danger-light">
                            <i class="fas fa-calendar-times text-danger"></i>
                        </div>
                        <div class="info-content">
                            <label class="info-label">Tanggal Pengembalian</label>
                            <h6 class="info-value mb-0"><?= date('d M Y', strtotime($data['info_peminjaman']['tanggal_pengembalian'])); ?></h6>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mt-2">
                    <div class="duration-box">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-clock text-navy fa-2x mr-3"></i>
                            <div>
                                <label class="info-label mb-1">Durasi Peminjaman</label>
                                <h5 class="mb-0 text-navy font-weight-bold">
                                    <?php
                                        $start = new DateTime($data['info_peminjaman']['tanggal_peminjaman']);
                                        $end = new DateTime($data['info_peminjaman']['tanggal_pengembalian']);
                                        $diff = $start->diff($end);
                                        echo $diff->days . ' Hari';
                                    ?>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-modern">
        <div class="card-header bg-gradient-navy text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-boxes mr-2"></i>Daftar Barang Dipinjam
                </h6>
                <span class="badge badge-light-navy px-3 py-2">
                    <i class="fas fa-cube mr-1"></i>Total: <?= count($data['detail_barang']); ?> Item
                </span>
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th class="pl-4 py-3" width="5%">No</th>
                            <th class="py-3" width="12%">Gambar</th>
                            <th class="py-3" width="35%">Detail Barang</th>
                            <th class="text-center py-3" width="15%">Kode Barang</th>
                            <th class="text-center py-3" width="13%">Jumlah</th>
                            <th class="text-center py-3" width="15%">Kondisi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        if (empty($data['detail_barang'])) : ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted mb-0">Tidak ada barang dalam transaksi ini.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($data['detail_barang'] as $item) : ?>
                                <tr class="item-row">
                                    <td class="pl-4">
                                        <div class="number-badge"><?= $no++; ?></div>
                                    </td>
                                    
                                    <td>
                                        <div class="img-wrapper-table">
                                            <?php 
                                                $foto = !empty($item['foto_barang']) ? BASEURL . '../public/img/foto-barang/' . $item['foto_barang'] : 'https://via.placeholder.com/80?text=No+Image';
                                            ?>
                                            <img src="<?= $foto; ?>" alt="Foto" class="img-fluid">
                                        </div>
                                    </td>

                                    <td>
                                        <div class="item-name">
                                            <h6 class="mb-1 font-weight-bold text-dark"><?= $item['nama_barang']; ?></h6>
                                            <small class="text-muted">Barang Inventaris</small>
                                        </div>
                                    </td>
                                    
                                    <td class="text-center">
                                        <span class="code-badge">
                                            <i class="fas fa-barcode mr-1"></i><?= $item['kode_barang']; ?>
                                        </span>
                                    </td>
                                    
                                    <td class="text-center">
                                        <span class="quantity-badge">
                                            <?= $item['jumlah']; ?> <small>Unit</small>
                                        </span>
                                    </td>
                                    
                                    <td class="text-center">
                                        <span class="badge badge-kondisi">
                                            <i class="fas fa-check-circle mr-1"></i><?= $item['kondisi'] ?? 'Baik'; ?>
                                        </span>
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