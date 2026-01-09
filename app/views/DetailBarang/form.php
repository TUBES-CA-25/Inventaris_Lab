<?php
if (!isset($_SESSION['login'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}
?>

<div class="content">
    <div class="container-fluid p-4">

        <div class="form-card">
            
            <a href="<?= BASEURL; ?>DetailBarang" class="btn-close-absolute">
                <i class="fa-solid fa-times"></i>
            </a>

            <h2 class="form-title">Tambah barang</h2>

            <form action="<?= BASEURL ?>DetailBarang/tambahBarang" method="post" enctype="multipart/form-data">
                
                <div class="form-grid">
                    
                    <div class="left-column">
                        
                        <div class="form-group mb-4">
                            <label class="form-label">Jenis barang</label>
                            <select name="sub_barang" class="form-select" required>
                                <option value="">-- Pilih Jenis --</option>
                                <?php foreach ($data['sub_barang'] as $opt) { ?>
                                    <option value="<?= $opt['id_jenis_barang'] ?>"><?= $opt['sub_barang'] ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label">Deskripsi</label>
                            <input type="text" name="deskripsi_barang" class="form-input" placeholder="Masukkan deskripsi...">
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label">Jumlah</label>
                            <input type="number" name="jumlah_barang" class="form-input" min="1" value="1" required>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label">Tgl pengadaan</label>
                            <input type="date" name="tgl_pengadaan_barang" class="form-input" required>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label">Lokasi Penyimpanan</label>
                            <select name="lokasi_penyimpanan" class="form-select" required>
                                <option value="">-- Pilih Lokasi --</option>
                                <?php foreach ($data['lokasiPenyimpanan'] as $opt) { ?>
                                    <option value="<?= $opt['id_lokasi_penyimpanan'] ?>"><?= $opt['nama_lokasi_penyimpanan'] ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="">-- Pilih Status --</option>
                                <?php foreach ($data['status'] as $opt) { ?>
                                    <option value="<?= $opt['id_status'] ?>"><?= $opt['status'] ?></option>
                                <?php } ?>
                            </select>
                        </div>

                    </div>

                    <div class="right-column">
                        
                        <div class="form-group mb-4">
                            <label class="form-label">Merek Barang</label>
                            <select name="nama_merek_barang" class="form-select" required>
                                <option value="">-- Pilih Merek --</option>
                                <?php foreach ($data['nama_merek_barang'] as $opt) { ?>
                                    <option value="<?= $opt['id_merek_barang'] ?>"><?= $opt['nama_merek_barang'] ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label">Kondisi barang</label>
                            <select name="kondisi_barang" class="form-select" required>
                                <option value="">-- Pilih Kondisi --</option>
                                <?php foreach ($data['kondisiBarang'] as $opt) { ?>
                                    <option value="<?= $opt['id_kondisi_barang'] ?>"><?= $opt['kondisi_barang'] ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label">Satuan</label>
                            <select name="satuan" class="form-select" required>
                                <option value="">-- Pilih Satuan --</option>
                                <?php foreach ($data['satuan'] as $opt) { ?>
                                    <option value="<?= $opt['id_satuan'] ?>"><?= $opt['nama_satuan'] ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label">Keterangan label</label>
                            <select name="keterangan_label" class="form-select" required>
                                <option value="">-- Pilih --</option>
                                <option value="sudah">Sudah</option>
                                <option value="belum">Belum</option>
                            </select>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label">Detail penyimpanan</label>
                            <input type="text" name="deskripsi_detail_lokasi" class="form-input" placeholder="Contoh: Rak 2, Lemari B...">
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label">Upload Foto</label>
                            <input type="file" name="foto_barang" class="form-input" accept="image/*">
                        </div>

                    </div>

                </div>
                
                <div style="margin-top: 10px; border-top: 1px dashed #ddd; padding-top: 20px;">
                    <p style="font-size: 13px; font-weight: 600; color: #888; margin-bottom: 15px;">Data Penomoran (Untuk Kode Barang)</p>
                    <div class="form-grid" style="grid-template-columns: 1fr 1fr 1fr;">
                        <div class="form-group">
                            <label class="form-label" style="font-size: 13px;">Barang Ke-</label>
                            <input type="number" name="barang_ke" class="form-input" placeholder="Contoh: 1" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" style="font-size: 13px;">Total Barang</label>
                            <input type="number" name="total_barang" class="form-input" placeholder="Contoh: 10" required>
                        </div>
                         <div class="form-group">
                            <label class="form-label" style="font-size: 13px;">Status Peminjaman</label>
                            <select name="status_pinjam" class="form-select">
                                <option value="bisa">Bisa Dipinjam</option>
                                <option value="tidak bisa">Tidak Bisa</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="btn-submit-container">
                    <button type="submit" class="btn-submit">Kirim</button>
                </div>

            </form>

        </div>
    </div>
</div>