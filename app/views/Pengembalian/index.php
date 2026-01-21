<?php
if (!isset($_SESSION['login'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}
?>

<div class="container-fluid" style="padding-left: 280px; padding-top: 30px; padding-right: 30px;">
    <div class="card shadow-sm border-0" style="border-radius: 15px; background: white; padding: 25px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">Pengembalian</h3>
            <div class="input-group" style="width: 300px;">
                <input type="text" class="form-control" placeholder="Search...">
                <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
            </div>
        </div>
        
       <div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead style="background-color: #0d1b3e; color: white;">
            <tr>
                <th class="py-3 ps-3">No</th>
                <th>Judul kegiatan</th>
                <th>Tgl pengajuan</th>
                <th>Tgl mulai peminjaman</th>
                <th>Tgl akhir peminjaman</th>
                <th class="text-center">Status</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; foreach($data['riwayat'] as $r) : ?>
            <tr>
                <td class="ps-3"><?= $i++; ?></td>
                <td><?= $r['judul_kegiatan']; ?></td>
                <td><?= $r['tanggal_pengajuan']; ?></td>
                <td><?= $r['tanggal_peminjaman']; ?></td>
                <td><?= $r['tanggal_pengembalian']; ?></td>
                <td class="text-center">
                    <?php if($r['status'] == 'dikembalikan') : ?>
                        <span class="badge bg-success rounded-pill px-3">dikembalikan</span>
                    <?php else : ?>
                        <span class="badge bg-warning text-dark rounded-pill px-3">dipinjam</span>
                    <?php endif; ?>
                </td>
                <td class="text-center">
                    <?php if($r['status'] == 'dipinjam') : ?>
                    <a href="<?= BASEURL; ?>Pengembalian/input/<?= $r['id_peminjaman']; ?>" class="text-dark me-2" title="Input Pengembalian">
                        <i class="fas fa-edit"></i>
                    </a>
                    <?php endif; ?>

                    <a href="<?= BASEURL; ?>Pengembalian/detail/<?= $r['id_peminjaman']; ?>" class="text-dark" title="Lihat Detail">
                        <i class="fas fa-eye"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
    </div>
</div>