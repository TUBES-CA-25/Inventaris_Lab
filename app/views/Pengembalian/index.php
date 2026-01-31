<?php
if (!isset($_SESSION['login'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}
?>

<meta name="base-url" content="<?= BASEURL; ?>">
<link rel="stylesheet" href="<?= BASEURL; ?>css/pengembalianIndex.css">

<div class="content">
    <div class="content-beranda">
        <!-- Header Section -->
        <div class="pengembalian-header">
            <h3 class="pengembalian-title">Pengembalian</h3>

            <!-- Search Box -->
            <div class="pengembalian-search-box">
                <input type="text" id="searchInput" class="pengembalian-search-input" placeholder="Search...">
                <i class="fas fa-filter pengembalian-search-icon"></i>
            </div>
        </div>

        <!-- Flash Message -->
        <div class="flash mb-3">
            <?php Flasher::flash(); ?>
        </div>

        <!-- Table Container -->
        <div class="pengembalian-table-wrapper">
            <table class="table table-hover align-middle pengembalian-table" id="pengembalianTable">
                <thead class="pengembalian-thead">
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
                    <?php
                    $i = 1;
                    if (!empty($data['riwayat'])):
                        foreach ($data['riwayat'] as $r):
                            // Default status jika belum ada data di trx_pengembalian
                            $status_display = 'Belum Diperiksa';
                            $status_class = 'bg-secondary text-white'; // Abu-abu (Netral)

                            // Cek jika data pengembalian sudah ada
                            if (!empty($r['status_pengembalian'])) {
                                $status_display = $r['status_pengembalian'];

                                switch ($r['status_pengembalian']) {
                                    case 'Selesai Periksa':
                                        $status_class = 'bg-success text-white'; // Hijau (Aman)
                                        break;
                                    case 'Periksa':
                                        $status_class = 'bg-primary text-white'; // Biru (Sedang Proses)
                                        break;
                                    case 'Periksa Ulang':
                                        $status_class = 'bg-danger text-white'; // Merah (Masalah)
                                        break;
                                    default:
                                        $status_class = 'bg-warning text-dark'; // Kuning (Lainnya)
                                        break;
                                }
                            }
                    ?>
                            <tr class="pengembalian-table-row">
                                <td class="ps-3"><?= $i++; ?></td>
                                <td><?= htmlspecialchars($r['judul_kegiatan']); ?></td>
                                <td><?= date('d/m/Y', strtotime($r['tanggal_pengajuan'])); ?></td>
                                <td><?= date('d/m/Y', strtotime($r['tanggal_peminjaman'])); ?></td>
                                <td><?= date('d/m/Y', strtotime($r['tanggal_pengembalian'])); ?></td>
                                <td class="text-center">
                                    <span class="status-badge <?= $status_class ?>">
                                        <?= $status_display ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="<?= BASEURL; ?>Pengembalian/edit/<?= $r['id_peminjaman']; ?>"
                                            class="btn-icon-action edit-icon" title="Edit Status Pengembalian">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>

                                        <a href="<?= BASEURL; ?>Pengembalian/detail/<?= $r['id_peminjaman']; ?>"
                                            class="btn-icon-action detail-icon" title="Detail Pengembalian">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php
                        endforeach;
                    else:
                        ?>
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-inbox fa-3x mb-3 empty-state-icon"></i>
                                <p class="mb-0">Tidak ada data peminjaman</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="<?= BASEURL; ?>js/pengembalian_index.js"></script>