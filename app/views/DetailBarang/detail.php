<?php
if (!isset($_SESSION['login'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}
?>

<style>
    
</style>

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
                        
                        <a href="<?= BASEURL . $item['qr_code']; ?>" download="QR_<?= $item['kode_barang']; ?>"" 
                           class="btn-action-custom btn-navy-slide">
                            <i class="fa-solid fa-qrcode"></i> Generate QR Code
                        </a>

                        <form action="<?= BASEURL; ?>DetailBarang/cetak" method="post" target="_blank" style="width:100%;">
                            <input type="hidden" name="id_barang[]" value="<?= $item['id_barang']; ?>">
                            <button type="submit" class="btn-action-custom btn-navy-slide">
                                <i class="fa-solid fa-file-pdf"></i> Ekspor PDF
                            </button>
                        </form>

                        <a href="<?= BASEURL; ?>DetailBarang/ubah/<?= $item['id_barang']; ?>" 
                           class="btn-action-custom btn-navy-slide">
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </a>
                        
                        <a href="<?= BASEURL; ?>DetailBarang" 
                           class="btn-action-custom btn-gray-slide">
                            <i class="fa-solid fa-arrow-left"></i> Kembali
                        </a>

                    </div>
                </div>

            </div>
        </div>

    </div>
</div>