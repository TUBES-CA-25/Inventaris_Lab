<?php
if (!isset($_SESSION['login'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}
?>
<div class="content">
    <style>
        
    </style>

    <div class="container-fluid">
        <div class="form-card">
            <form action="<?= BASEURL ?>Peminjaman/prosesTambahPeminjaman" method="post">
                
                <div class="row">
                    <div class="col-md-7">
                        <h2 class="form-title">Peminjaman</h2>

                        <div class="gap-row">
                            <label class="lbl">Judul kegiatan</label>
                            <input type="text" name="judul_kegiatan" class="inp-custom" required>
                        </div>

                        <div class="gap-row">
                            <label class="lbl">Tanggal pengajuan</label>
                            <div class="icon-wrap">
                                <input type="date" name="tanggal_pengajuan" class="inp-custom" value="<?= date('Y-m-d'); ?>" required>
                                <i class="fa-regular fa-calendar icon-inside" style="color: #1e293b;"></i>
                            </div>
                        </div>

                        <div class="row row-item-grid gap-row">
                            <div class="col-md-6">
                                <label class="lbl">Mulai dari tanggal</label>
                                <div class="icon-wrap">
                                    <input type="date" name="tanggal_peminjaman" class="inp-custom" required>
                                    <i class="fa-regular fa-calendar icon-inside" style="color: #1e293b;"></i>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="lbl">Sampai tanggal</label>
                                <div class="icon-wrap">
                                    <input type="date" name="tanggal_pengembalian" class="inp-custom" required>
                                    <i class="fa-regular fa-calendar icon-inside" style="color: #1e293b;"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5 right-section">
                        <div class="content-figure">
                            <img id="img-figure-daftar" src="<?= BASEURL ?>img/happy robot assistant.svg" alt="figure" />
                            <div class="hello-text">Hello! 👋</div>
                        </div>
                    </div>
                </div>

                <hr style="border-top: 2px dashed #e2e8f0; margin: 30px 0;">

                <div class="row">
                    <div class="col-12">
                        <?php if(!empty($data['barang_selected'])): ?>
                            
                            <?php foreach($data['barang_selected'] as $idx => $item): ?>
                                <div class="item-row">
                                    <div class="row row-item-grid align-items-end">
                                        <div class="col-md-5">
                                            <label class="lbl">Jenis Barang</label>
                                            <div class="icon-wrap">
                                                <input type="hidden" name="id_jenis_barang[]" value="<?= $item['id_jenis_barang']; ?>">
                                                <input type="text" class="inp-custom inp-readonly" value="<?= $item['sub_barang']; ?>" readonly>
                                                <i class="fa-solid fa-check icon-inside" style="color: #22c55e; font-size: 18px;"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="lbl">Jumlah</label>
                                            <input type="number" name="jumlah_peminjaman[]" class="inp-custom" min="1" value="1" required style="text-align: center;">
                                        </div>
                                        <div class="col-md-5">
                                            <label class="lbl">Keterangan</label>
                                            <input type="text" name="keterangan_peminjaman[]" class="inp-custom" placeholder="-">
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <div class="add-more-container">
                                <a href="<?= BASEURL; ?>Peminjaman" class="btn-add-more" title="Tambah Barang Lain">
                                    <i class="fa-solid fa-plus"></i>
                                </a>
                            </div>

                        <?php else: ?>
                            <div class="alert alert-warning">
                                Keranjang kosong. <a href="<?= BASEURL; ?>Peminjaman" class="alert-link">Pilih Barang Dulu</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="action-footer">
                    <a href="<?= BASEURL; ?>Peminjaman" class="btn-back">Kembali</a>
                    
                    <?php if(!empty($data['barang_selected'])): ?>
                        <button type="submit" class="btn-send">Kirim</button>
                    <?php endif; ?>
                </div>

            </form>
        </div>
    </div>
</div>