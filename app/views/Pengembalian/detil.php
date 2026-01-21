<?php
if (!isset($_SESSION['login'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}
?>

<div class="container-fluid" style="padding-left: 280px; padding-top: 20px; padding-right: 30px;">
    <div class="d-flex justify-content-between align-items-center mb-3 text-white p-3" style="background-color: #0d1b3e; border-radius: 5px;">
        <h5 class="m-0 text-center w-100">Detail Pengembalian</h5>
        <a href="<?= BASEURL; ?>Pengembalian" class="text-white text-decoration-none fw-bold">X</a>
    </div>

    <div class="bg-white p-4 shadow-sm" style="border-radius: 10px;">
        <h5 class="fw-bold mb-3">Data Peminjaman</h5>
        <div class="table-responsive mb-5">
            <table class="table table-bordered text-center align-middle">
                <thead style="background-color: #0d1b3e; color: white;">
                    <tr>
                        <th>No</th>
                        <th>Judul kegiatan</th>
                        <th>Tgl pengajuan</th>
                        <th>Tgl mulai</th>
                        <th>Tgl akhir</th>
                        <th>Jenis barang</th>
                        <th>Jumlah</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td><?= $data['detail']['judul_kegiatan']; ?></td>
                        <td><?= $data['detail']['tanggal_pengajuan']; ?></td>
                        <td><?= $data['detail']['tanggal_peminjaman']; ?></td>
                        <td><?= $data['detail']['tanggal_pengembalian']; ?></td>
                        <td><?= $data['detail']['nama_barang'] ?? '-'; ?></td>
                        <td><?= $data['detail']['jumlah'] ?? '0'; ?></td>
                        <td><?= $data['detail']['keterangan'] ?? '-'; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h5 class="fw-bold mb-3">Data Pengembalian</h5>
        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle">
                <thead style="background-color: #0d1b3e; color: white;">
                    <tr>
                        <th>No</th>
                        <th>Tgl Cek Kembali</th>
                        <th>Keterangan Petugas</th>
                        <th>Detail Masalah</th>
                        <th>ID Petugas</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td><?= $data['detail']['tgl_kembali'] ?? '-'; ?></td>
                        <td><?= $data['detail']['keterangan_kembali'] ?? '-'; ?></td>
                        <td><?= $data['detail']['detail_masalah'] ?? 'Tidak ada masalah'; ?></td>
                        <td><?= $data['detail']['id_petugas'] ?? '-'; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>