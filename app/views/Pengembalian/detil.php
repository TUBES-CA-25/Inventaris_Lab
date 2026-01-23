<?php
if (!isset($_SESSION['login'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}
?>

<style>
    /* Full Page Overlay Styling */
    .detail-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 9998;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .detail-container {
        position: relative;
        background: white;
        width: 95%;
        max-width: 1200px;
        max-height: 90vh;
        overflow-y: auto;
        border-radius: 10px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        z-index: 9999;
    }

    .detail-header {
        background-color: #0d1b3e;
        color: white;
        padding: 20px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .detail-header h3 {
        margin: 0;
        font-size: 24px;
        font-weight: 600;
    }

    .close-btn {
        color: white;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        background: none;
        border: none;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s ease;
    }

    .close-btn:hover {
        transform: scale(1.1);
    }

    .detail-content {
        padding: 30px;
    }

    .section-title {
        color: #0d1b3e;
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #0d1b3e;
    }

    .search-box-detail {
        position: relative;
        width: 280px;
        margin-bottom: 20px;
    }

    .search-box-detail input {
        width: 100%;
        padding: 8px 35px 8px 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 14px;
    }

    .search-box-detail i {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #0d1b3e;
    }

    /* Custom Table Styling */
    .custom-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 40px;
        border-radius: 8px;
        overflow: hidden;
    }

    .custom-table thead {
        background-color: #0d1b3e;
        color: white;
    }

    .custom-table th {
        padding: 15px;
        text-align: left;
        font-weight: 500;
        font-size: 14px;
    }

    .custom-table tbody tr {
        border-bottom: 1px solid #e0e0e0;
        transition: background-color 0.2s ease;
    }

    .custom-table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .custom-table td {
        padding: 15px;
        font-size: 14px;
        color: #333;
    }

    .custom-table tbody tr:last-child {
        border-bottom: none;
    }

    /* Scrollbar */
    .detail-container::-webkit-scrollbar {
        width: 8px;
    }

    .detail-container::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .detail-container::-webkit-scrollbar-thumb {
        background: #0d1b3e;
        border-radius: 4px;
    }
</style>

<div class="detail-overlay">
    <div class="detail-container">
        <!-- Header -->
        <div class="detail-header">
            <h3>Detail Pengembalian</h3>
            <a href="<?= BASEURL; ?>Pengembalian" class="close-btn">&times;</a>
        </div>

        <!-- Content -->
        <div class="detail-content">
            <!-- Search Box -->
            <div class="d-flex justify-content-end">
                <div class="search-box-detail">
                    <input type="text" id="detailSearch" placeholder="Search...">
                    <i class="fas fa-filter"></i>
                </div>
            </div>

            <!-- Data Peminjaman Section -->
            <h4 class="section-title">Data Peminjaman</h4>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul kegiatan</th>
                            <th>Tgl pengajuan</th>
                            <th>Tgl mulai peminjaman</th>
                            <th>Tgl akhir peminjaman</th>
                            <th>Jenis barang</th>
                            <th>Jumlah</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td><?= htmlspecialchars($data['detail']['judul_kegiatan'] ?? '-'); ?></td>
                            <td><?= !empty($data['detail']['tanggal_pengajuan']) ? date('d/m/Y', strtotime($data['detail']['tanggal_pengajuan'])) : '-'; ?>
                            </td>
                            <td><?= !empty($data['detail']['tanggal_peminjaman']) ? date('d/m/Y', strtotime($data['detail']['tanggal_peminjaman'])) : '-'; ?>
                            </td>
                            <td><?= !empty($data['detail']['tanggal_pengembalian']) ? date('d/m/Y', strtotime($data['detail']['tanggal_pengembalian'])) : '-'; ?>
                            </td>
                            <td><?= htmlspecialchars($data['detail']['nama_barang'] ?? 'Belum ada data'); ?></td>
                            <td><?= htmlspecialchars($data['detail']['jumlah'] ?? '0'); ?></td>
                            <td><?= htmlspecialchars($data['detail']['keterangan_peminjaman'] ?? '-'); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Data Pengembalian Section -->
            <h4 class="section-title">Data Pengembalian</h4>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul kegiatan</th>
                            <th>Tgl pengajuan</th>
                            <th>Tgl mulai peminjaman</th>
                            <th>Tgl akhir peminjaman</th>
                            <th>Jenis barang</th>
                            <th>Jumlah</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['detail']['id_pengembalian'])): ?>
                            <tr>
                                <td>1</td>
                                <td><?= htmlspecialchars($data['detail']['judul_kegiatan'] ?? '-'); ?></td>
                                <td><?= !empty($data['detail']['tgl_pengembalian_aktual']) ? date('d/m/Y', strtotime($data['detail']['tgl_pengembalian_aktual'])) : '-'; ?>
                                </td>
                                <td><?= !empty($data['detail']['tanggal_peminjaman']) ? date('d/m/Y', strtotime($data['detail']['tanggal_peminjaman'])) : '-'; ?>
                                </td>
                                <td><?= !empty($data['detail']['tanggal_pengembalian']) ? date('d/m/Y', strtotime($data['detail']['tanggal_pengembalian'])) : '-'; ?>
                                </td>
                                <td><?= htmlspecialchars($data['detail']['nama_barang'] ?? '-'); ?></td>
                                <td><?= htmlspecialchars($data['detail']['jumlah'] ?? '0'); ?></td>
                                <td>
                                    <?php
                                    // Gabungkan keterangan dan detail masalah
                                    $keterangan_parts = [];
                                    if (!empty($data['detail']['keterangan'])) {
                                        $keterangan_parts[] = $data['detail']['keterangan'];
                                    }
                                    if (!empty($data['detail']['detail_masalah']) && $data['detail']['detail_masalah'] != '-') {
                                        $keterangan_parts[] = 'Detail: ' . $data['detail']['detail_masalah'];
                                    }
                                    if (!empty($data['detail']['status_pengembalian'])) {
                                        $keterangan_parts[] = 'Status: ' . $data['detail']['status_pengembalian'];
                                    }
                                    echo !empty($keterangan_parts) ? htmlspecialchars(implode(' | ', $keterangan_parts)) : '-';
                                    ?>
                                </td>
                            </tr>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center" style="padding: 30px; color: #999;">
                                    <i class="fas fa-info-circle fa-2x mb-2" style="color: #ddd;"></i>
                                    <p class="mb-0">Belum ada data pengembalian</p>
                                    <small>Barang masih dalam status dipinjam</small>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if (!empty($data['detail']['id_pengembalian'])): ?>
                <!-- Informasi Petugas -->
                <div class="mt-4 p-3" style="background: #f8f9fa; border-radius: 8px; border-left: 4px solid #0d1b3e;">
                    <h6 style="color: #0d1b3e; margin-bottom: 10px;">
                        <i class="fas fa-user-tie"></i> Informasi Pengembalian
                    </h6>
                    <div class="row">
                        <div class="col-md-4">
                            <small class="text-muted">Diterima oleh:</small>
                            <p class="mb-0 fw-bold">
                                <?= htmlspecialchars($data['detail']['nama_petugas'] ?? 'Petugas tidak diketahui'); ?></p>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">Tanggal Pengembalian:</small>
                            <p class="mb-0 fw-bold">
                                <?= !empty($data['detail']['tgl_pengembalian_aktual']) ? date('d F Y', strtotime($data['detail']['tgl_pengembalian_aktual'])) : '-'; ?>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">Status:</small>
                            <p class="mb-0">
                                <?php
                                $status = $data['detail']['status_pengembalian'] ?? 'Belum Dikembalikan';
                                $badge_class = '';
                                switch ($status) {
                                    case 'Dikembalikan':
                                        $badge_class = 'bg-success';
                                        break;
                                    case 'Rusak':
                                        $badge_class = 'bg-danger';
                                        break;
                                    case 'Hilang':
                                        $badge_class = 'bg-dark';
                                        break;
                                    default:
                                        $badge_class = 'bg-warning text-dark';
                                }
                                ?>
                                <span class="badge <?= $badge_class ?>"><?= htmlspecialchars($status); ?></span>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('detailSearch');
    const tables = document.querySelectorAll('.custom-table');

    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const filter = searchInput.value.toLowerCase();

            tables.forEach(table => {
                const rows = table.querySelectorAll('tbody tr');
                rows.forEach(row => {
                    const cells = row.getElementsByTagName('td');
                    let found = false;

                    for (let i = 0; i < cells.length; i++) {
                        const cellText = cells[i].textContent || cells[i].innerText;
                        if (cellText.toLowerCase().indexOf(filter) > -1) {
                            found = true;
                            break;
                        }
                    }

                    row.style.display = found ? '' : 'none';
                });
            });
        });
    }

    // Close on overlay click
    document.querySelector('.detail-overlay').addEventListener('click', function(e) {
        if (e.target === this) {
            window.location.href = '<?= BASEURL; ?>Pengembalian';
        }
    });

    // Close on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            window.location.href = '<?= BASEURL; ?>Pengembalian';
        }
    });
});
</script>