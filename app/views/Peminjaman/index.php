<?php
if (!isset($_SESSION['login'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}
?>



<div class="content">
    <div class="container-fluid p-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 style="font-weight: 700; color: #1e293b;">Barang Laboratorium</h3>

            <div class="search-wrapper" style="position: relative; width: 300px;">
                <form action="<?= BASEURL; ?>Peminjaman/cari" method="post">
                    <input type="text" class="form-control"
                        placeholder="Search..."
                        name="keyword"
                        style="border-radius: 8px; padding-right: 40px;">
                    <i class="fas fa-filter" style="position: absolute; right: 15px; top: 12px; color: #94a3b8;"></i>
                </form>
            </div>
        </div>

        <div class="row">
            <?php if (!empty($data['barang'])) : ?>
                <?php foreach ($data['barang'] as $brg) : ?>
                    <div class="col-12 col-sm-6 col-md-4 col-xl-3 mb-4">
                        <div class="h-100 shadow-sm" style="border-radius: 12px; border: none; overflow: hidden;">

                            <div class="card-img-wrapper d-flex align-items-center justify-content-center p-3" style="height: 180px; background: #fff;">
                                <?php
                                $gambar = !empty($brg['gambar']) ? $brg['gambar'] : 'default_tools.png';
                                ?>
                                <img src="<?= BASEURL; ?>img/<?= $gambar; ?>"
                                    alt="<?= $brg['sub_barang']; ?>"
                                    style="max-height: 100%; max-width: 100%; object-fit: contain;">
                            </div>

                            <div class="card-body d-flex flex-column justify-content-between bg-light">
                                <h6 class="card-title font-weight-bold mb-3 text-dark">
                                    <?= $brg['sub_barang']; ?>
                                </h6>

                                <div class="card-barang">
                                    <a href="<?= BASEURL; ?>Peminjaman/tambahItem/<?= $brg['id_jenis_barang']; ?>"
                                        class="btn btn-block text-white btnPinjam"
                                        style="background-color: #0f1429; border-radius: 8px; text-decoration:none;">
                                        Pinjam
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="col-12 text-center py-5">
                    <h5 class="text-muted">Tidak ada data barang ditemukan.</h5>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- <div class="modal fade" id="modalPinjam" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Form Peminjaman</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= BASEURL ?>Peminjaman/tambahPeminjaman" method="post">
                    
                    <input type="hidden" name="id_jenis_barang" id="modal_id_barang">
                    
                    <div class="form-group">
                        <label>Barang yang dipinjam</label>
                        <input type="text" class="form-control" id="modal_nama_barang" readonly style="background-color: #e9ecef;">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Judul Kegiatan</label>
                                <input type="text" name="judul_kegiatan" class="form-control" required placeholder="Contoh: Praktikum Jaringan">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Peminjam</label>
                                <input type="text" name="nama_peminjam" class="form-control" required placeholder="Nama Anda">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Mulai Tanggal</label>
                                <input type="date" name="tanggal_peminjaman" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Sampai Tanggal</label>
                                <input type="date" name="tanggal_pengembalian" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                         <div class="col-md-4">
                            <div class="form-group">
                                <label>Jumlah</label>
                                <input type="number" name="jumlah_peminjaman" class="form-control" min="1" value="1" required>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Keterangan</label>
                                <input type="text" name="keterangan_peminjaman" class="form-control" placeholder="Keterangan tambahan...">
                            </div>
                        </div>
                    </div>
                    
                    <input type="hidden" name="status" value="diproses">

                    <div class="modal-footer px-0 pb-0">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn text-white" style="background-color: #0f1429;">Ajukan Peminjaman</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> -->

<script>
    $(document).ready(function() {
        $('.btnPinjam').on('click', function() {
            // Ambil data dari tombol yang diklik
            const idBarang = $(this).data('id');
            const namaBarang = $(this).data('nama');

            // Masukkan ke dalam input field di modal
            $('#modal_id_barang').val(idBarang);
            $('#modal_nama_barang').val(namaBarang);
        });
    });
</script>