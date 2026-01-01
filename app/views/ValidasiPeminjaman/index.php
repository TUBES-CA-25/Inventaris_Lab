<?php
if (!isset($_SESSION['login'])) {
    header("Location: " . BASEURL . "Login");
    exit;
}
?>
<div class="content">
    <div class="container-fluid p-4">
        <h1 class="page-title">Validasi Peminjaman</h1>

        <!-- Stats Cards -->
        <div class="stats-container">
            <div class="stat-card primary">
                <div class="stat-label">Total diterima</div>
                <div class="stat-value">120</div>
                <div class="stat-icon">
                    <i class="fas fa-check"></i>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-label">Total diproses</div>
                <div class="stat-value">150</div>
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-label">Total ditolak</div>
                <div class="stat-value">10</div>
                <div class="stat-icon">
                    <i class="fas fa-times"></i>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-label">Total Pengembalian</div>
                <div class="stat-value">10</div>
                <div class="stat-icon">
                    <i class="far fa-calendar-check"></i>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="table-container">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul kegiatan</th>
                            <th>Tgl pengajuan</th>
                            <th>Tgl mulai peminjaman</th>
                            <th>Tgl akhir peminjaman</th>
                            <th>Status</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php if (empty($data['peminjaman'])) : ?>
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada data peminjaman yang perlu divalidasi.</td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($data['peminjaman'] as $pinjam) : ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $pinjam['nama_peminjam']; ?></td>
                                    <td><?= $pinjam['tanggal_pengajuan']; ?></td>
                                    <td><?= date('d-m-Y', strtotime($pinjam['tanggal_peminjaman'])); ?></td>
                                    <td><?= date('d-m-Y', strtotime($pinjam['tanggal_pengembalian'])); ?></td>

                                    <td>
                                        <?php
                                        $badgeClass = 'badge-secondary';
                                        if ($pinjam['status'] == 'diproses') $badgeClass = 'badge-warning';
                                        if ($pinjam['status'] == 'disetujui') $badgeClass = 'badge-success';
                                        if ($pinjam['status'] == 'ditolak') $badgeClass = 'badge-danger';
                                        ?>
                                        <span class="badge <?= $badgeClass; ?>"><?= ucfirst($pinjam['status']); ?></span>
                                    </td>

                                    <td>
                                        <a href="<?= BASEURL; ?>ValidasiPeminjaman/detail/<?= $pinjam['id_peminjaman']; ?>" 
                                            class="btn-detail btn btn-sm btn-info tampilModalValidasi">
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