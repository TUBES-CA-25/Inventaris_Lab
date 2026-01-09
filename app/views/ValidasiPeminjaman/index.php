<?php
// Cek sesi login & role
if (!isset($_SESSION['login']) || !in_array($_SESSION['id_role'], ['1', '2', '3', '4'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}
?>

<div class="content">
    <div class="container-fluid p-4">
        <h1 class="page-title">Validasi Peminjaman</h1>

        <div class="row g-4">
            <div class="col-12 col-md-6 col-xl-3">
                <div class="stat-card bg-navy">
                    <div>
                        <div class="stat-label">Total diterima</div>
                        <div class="stat-value"><?= isset($data['total_disetujui']) ? $data['total_disetujui'] : 0; ?></div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-check"></i>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <div class="stat-card bg-white">
                    <div>
                        <div class="stat-label">Total diproses</div>
                        <div class="stat-value"><?= isset($data['total_diproses']) ? $data['total_diproses'] : 0; ?></div>
                    </div>
                    <div class="stat-icon icon-dark">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <div class="stat-card bg-white">
                    <div>
                        <div class="stat-label">Total ditolak</div>
                        <div class="stat-value"><?= isset($data['total_ditolak']) ? $data['total_ditolak'] : 0; ?></div>
                    </div>
                    <div class="stat-icon icon-dark">
                        <i class="fas fa-times"></i>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <div class="stat-card bg-white">
                    <div>
                        <div class="stat-label">Total Pengembalian</div>
                        <div class="stat-value"><?= isset($data['total_kembali']) ? $data['total_kembali'] : 0; ?></div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-box-open"></i> 
                    </div>
                </div>
            </div>
        </div>

        <div class="custom-table-container mt-4">
            <div class="table-responsive">
                <table id="tableValidasi" class="table mb-0" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Peminjam</th> <th>Tgl Pengajuan</th>
                            <th>Tgl Mulai</th>
                            <th>Tgl Akhir</th>
                            <th>Status</th>
                            <th>Keterangan</th> 
                            <th class="text-center">Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php if (empty($data['peminjaman'])) : ?>
                            <?php else : ?>
                            <?php foreach ($data['peminjaman'] as $pinjam) : ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    
                                    <td class="font-weight-bold"><?= htmlspecialchars($pinjam['nama_peminjam']); ?></td> 
                                    
                                    <td><?= date('d/m/Y', strtotime($pinjam['tanggal_pengajuan'])); ?></td>
                                    <td><?= date('d/m/Y', strtotime($pinjam['tanggal_peminjaman'])); ?></td>
                                    <td><?= date('d/m/Y', strtotime($pinjam['tanggal_pengembalian'])); ?></td>

                                    <td>
                                        <?php
                                        // Badge logic tetap menggunakan warna asli Bootstrap/Anda
                                        $status = strtolower($pinjam['status']);
                                        $badgeClass = 'bg-secondary';
                                        
                                        if ($status == 'diproses') $badgeClass = 'bg-warning text-dark';
                                        elseif ($status == 'disetujui') $badgeClass = 'bg-success';
                                        elseif ($status == 'ditolak') $badgeClass = 'bg-danger';
                                        elseif ($status == 'dikembalikan') $badgeClass = 'bg-primary';
                                        ?>
                                        <span class="badge rounded-pill <?= $badgeClass; ?> px-3 py-2">
                                            <?= ucfirst($pinjam['status']); ?>
                                        </span>
                                    </td>
                                    
                                    <td><?= !empty($pinjam['keterangan_peminjaman']) ? $pinjam['keterangan_peminjaman'] : '-'; ?></td> 

                                    <td class="text-center">
                                        <a href="<?= BASEURL; ?>ValidasiPeminjaman/detail/<?= $pinjam['id_peminjaman']; ?>" 
                                            class="btn-detail" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                        </a>
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

