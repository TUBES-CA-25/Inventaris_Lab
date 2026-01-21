<?php
if (!isset($_SESSION['login'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}
?>

<div class="content">
    <div class="container-fluid content-beranda p-4">

        <div class="header-section mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h3 class="page-title">Barang Laboratorium</h3>

            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="customSearch" placeholder="Search...">
            </div> 
        </div>

        <div class="row">
            <?php if (!empty($data['barang'])) : ?>
                <?php foreach ($data['barang'] as $brg) : ?>
                    <?php
                    $fotoPath = 'default_tools.png'; 

                    if (!empty($brg['foto_barang'])) {
                        $cleanPath = str_replace('../public/img/', '', $brg['foto_barang']);
                        $fotoPath = $cleanPath;
                    }
                    ?>

                    <div class="col-12 col-sm-6 col-md-4 col-xl-3 mb-4">
                        <div class="card-item">
                            <div class="card-img-container">
                                <img src="<?= BASEURL; ?>img/<?= $fotoPath; ?>"
                                    alt="<?= $brg['sub_barang']; ?>"
                                    onerror="this.src='<?= BASEURL; ?>img/default_tools.png';">
                            </div>

                            <div class="card-desc">
                                <h6 class="barang-title"><?= $brg['sub_barang']; ?></h6>
                                <a href="<?= BASEURL; ?>Peminjaman/tambahItem/<?= IdObfuscator::encode($brg['id_jenis_barang']); ?>"
                                    class="btn-pinjam-now">
                                    Pinjam
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="col-12 text-center py-5">
                    <img src="<?= BASEURL; ?>img/empty_state.svg" width="200" style="opacity: 0.5;">
                    <h5 class="text-muted mt-3">Barang tidak ditemukan.</h5>
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