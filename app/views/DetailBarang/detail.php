<?php
if (!isset($_SESSION['login'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}
?>

<div class="content">
    <div class="container-fluid p-4">

        <?php $item = $data['dataTampilDetailBarang']; ?>

        <div class="detail-card">

            <h2 class="section-title">Detail Barang</h2>

            <div class="detail-layout">

                <div class="left-panel">
                    <div class="data-grid">
                        <div class="data-item">
                            <span class="label">Kode Barang</span>
                            <span class="value"><?= $item['kode_barang']; ?></span>
                        </div>
                        <div class="data-item">
                            <span class="label">Tanggal Pengadaan</span>
                            <span class="value"><?= date('d/m/Y', strtotime($item['tgl_pengadaan_barang'])); ?></span>
                        </div>
                        <div class="data-item">
                            <span class="label">Detail Penyimpanan</span>
                            <span class="value"><?= $item['deskripsi_detail_lokasi']; ?></span>
                        </div>
                        <div class="data-item">
                            <span class="label">Lokasi Penyimpanan</span>
                            <span class="value"><?= $item['nama_lokasi_penyimpanan']; ?></span>
                        </div>
                        <div class="data-item">
                            <span class="label">Jenis Barang</span>
                            <span class="value"><?= $item['sub_barang']; ?></span>
                        </div>
                        <div class="data-item">
                            <span class="label">Merek Barang</span>
                            <span class="value"><?= $item['nama_merek_barang']; ?></span>
                        </div>
                        <div class="data-item">
                            <span class="label">Status Barang</span>
                            <span class="value"><?= $item['status_peminjaman']; ?></span>
                        </div>
                        <div class="data-item">
                            <span class="label">Kondisi Barang</span>
                            <span class="value"><?= $item['kondisi_barang']; ?></span>
                        </div>
                        <div class="data-item">
                            <span class="label">Deskripsi Barang</span>
                            <span class="value"><?= !empty($item['spesifikasi_barang']) ? $item['spesifikasi_barang'] : '-'; ?></span>
                        </div>
                        <div class="data-item">
                            <span class="label">Keterangan Label</span>
                            <span class="value"><?= $item['keterangan_label']; ?></span>
                        </div>
                        <div class="data-item">
                            <span class="label">Jumlah Barang</span>
                            <span class="value"><?= $item['jumlah_barang']; ?></span>
                        </div>
                        <div class="data-item">
                            <span class="label">Satuan Barang</span>
                            <span class="value"><?= $item['nama_satuan']; ?></span>
                        </div>
                    </div>
                </div>

                <div class="right-panel">
                    <div class="product-image-box">
                        <?php if (!empty($item['foto_barang'])) : ?>
                            <img src="<?= BASEURL . $item['foto_barang']; ?>" alt="Gambar Barang">
                        <?php else : ?>
                            <div style="text-align:center; color:#ccc;">
                                <i class="fa-solid fa-image fa-4x"></i>
                                <p style="font-size:12px; margin-top:10px;">No Image</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="action-buttons">

                        <button type="button" class="btn-action-custom btn-navy-slide" data-toggle="modal" data-target="#modalQR">
                            <i class="fa-solid fa-qrcode"></i> Generate QR Code
                        </button>

                        <form action="<?= BASEURL; ?>DetailBarang/cetak" method="post" target="_blank" style="width:100%;">
                            <input type="hidden" name="id_barang[]" value="<?= $item['id_barang']; ?>">
                            <a href="<?= BASEURL; ?>DetailBarang/cetakSatuan/<?= $item['id_barang']; ?>" target="_blank" class="btn-action-custom btn-navy-slide">
                                <i class="fa-solid fa-file-pdf"></i> Ekspor PDF
                            </a>
                        </form>

                        <?php if (isset($_SESSION['login']) && in_array($_SESSION['id_role'], ['1', '2', '3', '4'])) : ?>
                            <a href="<?= BASEURL; ?>DetailBarang/ubah/<?= $item['id_barang']; ?>"
                                class="btn-action-custom btn-navy-slide">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </a>
                        <?php endif; ?>


                        <a href="<?= BASEURL; ?>DetailBarang"
                            class="btn-action-custom btn-gray-slide">
                            <i class="fa-solid fa-arrow-left"></i> Kembali
                        </a>

                    </div>

                    <div class="modal fade" id="modalQR" tabindex="-1" role="dialog" aria-labelledby="modalQRLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalQRLabel">
                                        <i class="fa-solid fa-qrcode me-2"></i> QR Code Barang
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body text-center">

                                    <h4 class="mb-3"><?= $item['kode_barang']; ?></h4>

                                    <div class="qr-container">
                                        <?php
                                    
                                        $cleanQrPath = str_replace('../public/', '', $item['qr_code']);
                                        ?>

                                        <?php if (!empty($item['qr_code']) && file_exists(str_replace('../public/', '', $item['qr_code']))) : ?>
                                            <img src="<?= BASEURL . $cleanQrPath; ?>"
                                                alt="QR Code"
                                                style="width: 200px; height: 200px;">
                                        <?php else : ?>
                                            <div class="error-message">
                                                <i class="fa-solid fa-triangle-exclamation fa-2x mb-2"></i><br>
                                                QR Code File Tidak Ditemukan
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <p class="text-muted small">
                                        <i class="fa-solid fa-info-circle me-1"></i>
                                        Scan QR code ini untuk melihat detail inventaris
                                    </p>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                        <i class="fa-solid fa-times me-1"></i> Tutup
                                    </button>

                                    <?php if (!empty($item['qr_code'])) : ?>
                                        <a href="<?= BASEURL . $cleanQrPath; ?>"
                                            download="QR_<?= str_replace('/', '-', $item['kode_barang']); ?>.png"
                                            class="btn btn-primary">
                                            <i class="fa-solid fa-download"></i> Download QR
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var modalElement = document.getElementById('modalQR');
        document.body.appendChild(modalElement);
    });
</script>