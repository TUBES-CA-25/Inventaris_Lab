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
        
        <h3 class="font-weight-bold mb-4" style="color: #0c1740;">Riwayat Peminjaman</h3>

        <div class="row">
            <div class="col-12">
                <?php Flasher::flash(); ?>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="btn-group">
                <button class="btn btn-navy-dark mr-2 rounded shadow-sm"><i class="fas fa-file-pdf mr-1"></i> PDF</button>
                <button class="btn btn-navy-dark mr-2 rounded shadow-sm"><i class="fas fa-file-excel mr-1"></i> Excel</button>
                <button class="btn btn-navy-dark rounded shadow-sm"><i class="fas fa-print mr-1"></i> Print</button>
            </div>

            <div class="search-container">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-white border-right-0 rounded-left">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                    </div>
                    <input type="text" id="customSearch" class="form-control border-left-0 rounded-right" placeholder="Cari data...">
                </div>
            </div>
        </div>

        <div class="table-responsive shadow-sm bg-white p-0" style="border-radius: 8px; overflow: hidden;">
            <table id="tableRiwayat" class="table table-hover mb-0 w-100">
                <thead style="background-color: #0c1740; color: white;">
                    <tr>
                        <th class="py-3 pl-4" width="5%">No</th>
                        <th class="py-3">Judul Kegiatan</th>
                        <th class="py-3">Tgl Pengajuan</th>
                        <th class="py-3">Tgl Mulai</th> 
                        <th class="py-3">Tgl Akhir</th>
                        
                        <th class="py-3 text-center" id="th-status" style="cursor: pointer;" title="Klik untuk filter status">
                            Status <i id="icon-status" class="fas fa-filter ml-1" style="font-size: 10px; opacity: 0.7;"></i>
                        </th>
                        
                        <th class="py-3 text-center" width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white text-dark">
                    <?php 
                    $no = 1;
                    $list_data = isset($data['riwayat']) ? $data['riwayat'] : [];
                    
                    if (!empty($list_data)) : 
                        foreach ($list_data as $row) : ?>
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td class="pl-4 py-3 align-middle"><?= $no++; ?></td>
                                <td class="py-3 align-middle font-weight-bold"><?= $row['judul_kegiatan']; ?></td>
                                
                                <td class="py-3 align-middle" data-order="<?= $row['tanggal_pengajuan']; ?>">
                                    <?= date('d/m/Y', strtotime($row['tanggal_pengajuan'])); ?>
                                </td>
                                <td class="py-3 align-middle" data-order="<?= $row['tanggal_peminjaman']; ?>">
                                    <?= date('d/m/Y', strtotime($row['tanggal_peminjaman'])); ?>
                                </td>
                                <td class="py-3 align-middle" data-order="<?= $row['tanggal_pengembalian']; ?>">
                                    <?= date('d/m/Y', strtotime($row['tanggal_pengembalian'])); ?>
                                </td>
                                
                                <td class="py-3 align-middle text-center">
                                    <?php
                                        $st = strtolower($row['status']);
                                        $badgeClass = 'badge-secondary';
                                        $displayText = ucfirst($st);

                                        if ($st == 'disetujui' || $st == 'diterima') { 
                                            $displayText = 'Diterima'; 
                                            $badgeClass = 'badge-diterima'; 
                                        } elseif ($st == 'diproses') { 
                                            $displayText = 'Diproses';
                                            $badgeClass = 'badge-diproses'; 
                                        } elseif ($st == 'ditolak') { 
                                            $displayText = 'Ditolak';
                                            $badgeClass = 'badge-ditolak'; 
                                        } elseif ($st == 'dikembalikan') { 
                                            $displayText = 'Dikembalikan';
                                            $badgeClass = 'badge-dikembalikan'; 
                                        } elseif ($st == 'melengkapi surat') {
                                            $displayText = 'Melengkapi Surat';
                                            $badgeClass = 'badge-lengkapi';
                                        }
                                    ?>
                                    <span class="badge-custom <?= $badgeClass; ?>">
                                        <?= $displayText; ?>
                                    </span>
                                </td>
                                
                                <td class="py-3 align-middle text-center">
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