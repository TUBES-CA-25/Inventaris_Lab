<?php
if (!isset($_SESSION['login'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}
?>

<div class="container-fluid" style="padding-left: 280px; padding-top: 30px; padding-right: 30px;">
    <div class="card shadow-sm border-0" style="border-radius: 15px; background: white; padding: 40px;">
        <h3 class="fw-bold mb-5">Peminjaman</h3>
        
        <form action="<?= BASEURL; ?>Pengembalian/proses_input" method="POST">
            <input type="hidden" name="id_peminjaman" value="<?= $data['peminjaman']['id_peminjaman']; ?>">
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label">Judul kegiatan</label>
                    <input type="text" class="form-control bg-light" value="<?= $data['peminjaman']['judul_kegiatan']; ?>" readonly>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label">Tanggal pengajuan</label>
                    <input type="text" class="form-control bg-light" value="<?= $data['peminjaman']['tanggal_pengajuan']; ?>" readonly>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label">Mulai dari tanggal</label>
                    <input type="text" class="form-control bg-light" value="<?= $data['peminjaman']['tanggal_peminjaman']; ?>" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Sampai tanggal</label>
                    <input type="text" class="form-control bg-light" value="<?= $data['peminjaman']['tanggal_pengembalian']; ?>" readonly>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label">Jenis barang (Kondisi Fisik)</label>
                    <select name="keterangan_kembali" class="form-select shadow-sm">
                        <option value="Lengkap & Baik">Lengkap & Baik</option>
                        <option value="Rusak">Rusak</option>
                        <option value="Tidak Lengkap">Tidak Lengkap</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jumlah</label>
                    <input type="number" name="jumlah_kembali" class="form-control" value="<?= $data['peminjaman']['jumlah']; ?>" required>
                </div>
            </div>

            <div class="row mb-5">
                <div class="col-md-12">
                    <label class="form-label">Keterangan (Detail Masalah)</label>
                    <textarea name="detail_masalah" class="form-control" rows="3" placeholder="Masukkan detail jika ada kerusakan..."></textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary px-5 py-2" style="background-color: #0d1b3e; border: none; border-radius: 8px;">Kirim</button>
            </div>
        </form>
    </div>
</div>