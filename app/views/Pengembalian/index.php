<?php
if (!isset($_SESSION['login'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}
?>

<div class="content">
    <div class="content-beranda">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4" style="padding: 20px 0;">
            <h3 class="fw-bold" style="color: #0d1b3e; font-size: 28px; margin: 0;">Pengembalian</h3>

            <!-- Search Box -->
            <div class="position-relative" style="width: 320px;">
                <input type="text" id="searchInput" class="form-control" placeholder="Search..." style="
                        border: 1px solid #ddd;
                        border-radius: 8px;
                        padding: 10px 40px 10px 15px;
                        height: 42px;
                        background: white;
                    ">
                <i class="fas fa-filter position-absolute"
                    style="right: 15px; top: 50%; transform: translateY(-50%); color: #0d1b3e; cursor: pointer;"></i>
            </div>
        </div>

        <!-- Flash Message -->
        <div class="flash mb-3">
            <?php Flasher::flash(); ?>
        </div>

        <!-- Table Container -->
        <div class="table-responsive" style="border-radius: 10px; overflow: hidden;">
            <table class="table table-hover align-middle" id="pengembalianTable" style="margin-bottom: 0;">
                <thead style="background-color: #0d1b3e; color: white;">
                    <tr>
                        <th class="py-3 ps-3" style="font-weight: 500;">No</th>
                        <th style="font-weight: 500;">Judul kegiatan</th>
                        <th style="font-weight: 500;">Tgl pengajuan</th>
                        <th style="font-weight: 500;">Tgl mulai peminjaman</th>
                        <th style="font-weight: 500;">Tgl akhir peminjaman</th>
                        <th class="text-center" style="font-weight: 500;">Status</th>
                        <th class="text-center" style="font-weight: 500;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    if (!empty($data['riwayat'])):
                        foreach ($data['riwayat'] as $r):
                            // Tentukan status berdasarkan status_pengembalian
                            $status_display = 'Belum Dikembalikan';
                            $status_class = 'bg-warning text-dark';
                            $status_icon = '⏳';

                            // Jika sudah ada data pengembalian, ambil dari status_pengembalian
                            if (!empty($r['status_pengembalian'])) {
                                $status_display = $r['status_pengembalian'];

                                // Set warna dan icon berdasarkan status
                                switch ($r['status_pengembalian']) {
                                    case 'Dikembalikan':
                                        $status_class = 'bg-success text-white';
                                        $status_icon = '✅';
                                        break;
                                    case 'Rusak':
                                        $status_class = 'bg-danger text-white';
                                        $status_icon = '🔧';
                                        break;
                                    case 'Hilang':
                                        $status_class = 'bg-dark text-white';
                                        $status_icon = '❌';
                                        break;
                                    case 'Belum Dikembalikan':
                                    default:
                                        $status_class = 'bg-warning text-dark';
                                        $status_icon = '⏳';
                                        break;
                                }
                            }
                            ?>
                            <tr style="cursor: pointer; transition: background-color 0.2s ease;">
                                <td class="ps-3"><?= $i++; ?></td>
                                <td><?= htmlspecialchars($r['judul_kegiatan']); ?></td>
                                <td><?= date('d/m/Y', strtotime($r['tanggal_pengajuan'])); ?></td>
                                <td><?= date('d/m/Y', strtotime($r['tanggal_peminjaman'])); ?></td>
                                <td><?= date('d/m/Y', strtotime($r['tanggal_pengembalian'])); ?></td>
                                <td class="text-center">
                                    <span class="badge <?= $status_class ?> rounded-pill px-3 py-2"
                                        style="font-size: 12px; font-weight: 500;">
                                        <?= $status_icon ?>         <?= $status_display ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <!-- Edit Button - Selalu tampil untuk toggle status -->
                                        <a href="<?= BASEURL; ?>Pengembalian/edit/<?= $r['id_peminjaman']; ?>"
                                            class="btn btn-sm" title="Edit Status Pengembalian"
                                            style="background: none; border: none; padding: 5px;">
                                            <i class="fa-solid fa-pen-to-square" style="color: #30cc30; font-size: 18px;"></i>
                                        </a>

                                        <!-- View Button - Selalu tampil -->
                                        <a href="<?= BASEURL; ?>Pengembalian/detail/<?= $r['id_peminjaman']; ?>"
                                            class="btn btn-sm" title="Detail Pengembalian"
                                            style="background: none; border: none; padding: 5px;">
                                            <i class="fa-solid fa-eye" style="color: #1250ba; font-size: 18px;"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        endforeach;
                    else:
                        ?>
                        <tr>
                            <td colspan="7" class="text-center py-4" style="color: #666;">
                                <i class="fas fa-inbox fa-3x mb-3" style="color: #ddd;"></i>
                                <p class="mb-0">Tidak ada data peminjaman</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Custom Styling -->
<style>
    /* Table Hover Effect */
    #pengembalianTable tbody tr:hover {
        background-color: #f8f9fa;
    }

    /* Search Input Focus */
    #searchInput:focus {
        outline: none;
        border-color: #0d1b3e;
        box-shadow: 0 0 0 3px rgba(13, 27, 62, 0.1);
    }

    /* Badge Animation */
    .badge {
        transition: transform 0.2s ease;
    }

    .badge:hover {
        transform: scale(1.05);
    }

    /* Action Buttons Hover */
    .btn i {
        transition: transform 0.2s ease, color 0.2s ease;
    }

    .btn:hover i {
        transform: scale(1.2);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .content {
            padding: 15px;
        }

        #searchInput {
            width: 200px !important;
        }

        table {
            font-size: 0.9rem;
        }
    }

    /* Custom Scrollbar for table */
    .table-responsive::-webkit-scrollbar {
        height: 8px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: #0d1b3e;
        border-radius: 10px;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #1a2d5a;
    }
</style>

<!-- Search Functionality -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');
        const table = document.getElementById('pengembalianTable');
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

        searchInput.addEventListener('keyup', function () {
            const filter = searchInput.value.toLowerCase();

            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const cells = row.getElementsByTagName('td');
                let found = false;

                // Search through all cells except the action column
                for (let j = 0; j < cells.length - 1; j++) {
                    const cellText = cells[j].textContent || cells[j].innerText;
                    if (cellText.toLowerCase().indexOf(filter) > -1) {
                        found = true;
                        break;
                    }
                }

                row.style.display = found ? '' : 'none';
            }
        });

        // Row click effect
        Array.from(rows).forEach(row => {
            row.addEventListener('click', function (e) {
                // Don't trigger if clicking on action buttons
                if (!e.target.closest('.btn')) {
                    this.style.backgroundColor = '#e8f4f8';
                    setTimeout(() => {
                        this.style.backgroundColor = '';
                    }, 200);
                }
            });
        });
    });
</script>
