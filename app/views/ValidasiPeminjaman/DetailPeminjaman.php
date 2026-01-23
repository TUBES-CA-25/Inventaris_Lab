<?php
// CEK KEAMANAN AKSES
// if (!isset($_SESSION['login']) || !in_array($_SESSION['id_role'], ['1', '2'])) {
//     header("Location:" . BASEURL . "Login");
//     exit;
// }

$p = $data['peminjaman'];
$status_sekarang = strtolower($p['status']);
$role_login = $_SESSION['id_role']; // 1=Huzain, 2=Fatimah
?>

<style>
    .step-card {
        border: 1px solid #e3e6f0;
        border-radius: 0.5rem;
        background-color: #fff;
        margin-bottom: 1rem;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        padding: 15px;
    }

    .step-card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }

    .step-icon {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.2rem;
        margin-right: 15px;
        flex-shrink: 0;
    }

    .step-pending {
        background-color: #eaecf4;
        color: #858796;
        border: 2px solid #d1d3e2;
    }

    .step-active {
        background-color: #4e73df;
        color: white;
        border: 2px solid #4e73df;
        box-shadow: 0 0 10px rgba(78, 115, 223, 0.5);
    }

    .step-success {
        background-color: #1cc88a;
        color: white;
        border: 2px solid #1cc88a;
    }

    /* Form Tolak Animasi */
    .form-section-hidden {
        display: none;
        margin-top: 20px;
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="content">
    <div class="container-fluid p-4">

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Detail Peminjaman</h1>

            <?php
            $badgeColor = 'secondary';
            if ($status_sekarang == 'disetujui') $badgeColor = 'success';
            if ($status_sekarang == 'ditolak') $badgeColor = 'danger';
            if ($status_sekarang == 'diproses') $badgeColor = 'warning';
            ?>
            <span class="badge badge-<?= $badgeColor; ?> px-3 py-2" style="font-size: 1rem;">
                Status: <?= ucfirst($status_sekarang); ?>
            </span>
        </div>

        <div class="row">
            <div class="col-xl-7 col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Data Peminjam</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless" width="100%" cellspacing="0">
                                <tr>
                                    <th width="35%">Nama Peminjam</th>
                                    <td>: <strong><?= $p['nama_user']; ?></strong> (<?= $p['nim_nip']; ?>)</td>
                                </tr>
                                <tr>
                                    <th>Judul Kegiatan</th>
                                    <td>: <?= $p['judul_kegiatan']; ?></td>
                                </tr>
                                <tr>
                                    <th>Tanggal Pengajuan</th>
                                    <td>: <?= date('d F Y', strtotime($p['tanggal_pengajuan'])); ?></td>
                                </tr>
                                <tr>
                                    <th>Waktu Peminjaman</th>
                                    <td>: <span class="text-primary"><?= date('d M Y', strtotime($p['tanggal_peminjaman'])); ?></span> s/d <span class="text-primary"><?= date('d M Y', strtotime($p['tanggal_pengembalian'])); ?></span></td>
                                </tr>
                                <tr>
                                    <th>Keterangan</th>
                                    <td>: <?= $p['keterangan_peminjaman'] ? $p['keterangan_peminjaman'] : '-'; ?></td>
                                </tr>
                            </table>
                        </div>

                        <hr>
                        <h6 class="font-weight-bold text-gray-800 ml-2">Barang yang Dipinjam:</h6>
                        <ul class="list-group list-group-flush mt-2">
                            <?php if (!empty($data['detail_barang'])) : foreach ($data['detail_barang'] as $item) : ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-box mr-2 text-gray-400"></i> <?= htmlspecialchars($item['nama_barang']); ?></span>
                                        <span class="badge badge-primary badge-pill"><?= $item['jumlah']; ?> Unit</span>
                                    </li>
                                <?php endforeach;
                            else : ?><li class="list-group-item text-muted">Tidak ada data barang.</li><?php endif; ?>
                            <li class="list-group-item active text-center font-weight-bold">Total: <?= $p['jumlah_peminjaman']; ?> Unit</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-xl-5 col-lg-6">

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Surat Permohonan</h6>
                    </div>
                    <div class="card-body text-center">
                        <?php if (!empty($p['file_surat'])) : ?>
                            <div class="mb-3">
                                <i class="fas fa-file-pdf text-danger" style="font-size: 50px;"></i>
                                <p class="mt-2 small text-muted"><?= $p['file_surat']; ?></p>
                            </div>
                            <a href="<?= BASEURL; ?>files/surat-peminjaman/<?= $p['file_surat']; ?>" target="_blank" class="btn btn-danger btn-icon-split btn-sm">
                                <span class="icon text-white-50"><i class="fas fa-download"></i></span>
                                <span class="text">Download / Lihat Surat</span>
                            </a>
                        <?php else : ?>
                            <div class="alert alert-warning">File surat belum diupload.</div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ($status_sekarang == 'diproses') : ?>
                    <div class="card shadow mb-4 border-bottom-warning">
                        <div class="card-header py-3 bg-warning text-white">
                            <h6 class="m-0 font-weight-bold">Proses Validasi</h6>
                        </div>
                        <div class="card-body">

                            <div class="step-card">
                                <div class="step-icon <?= ($p['validasi_kalab'] == '1') ? 'step-success' : 'step-pending'; ?>">
                                    <?= ($p['validasi_kalab'] == '1') ? '<i class="fas fa-check"></i>' : '1'; ?>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="font-weight-bold mb-0 text-gray-800">Kepala Lab</h6>
                                    <small>Huzain Aziz</small>
                                </div>

                                <div>
                                    <?php if ($role_login == '1' && $p['validasi_kalab'] == '0') : ?>
                                        <form id="formAccKalab" action="<?= BASEURL; ?>ValidasiPeminjaman/accKalab" method="post" class="d-inline">
                                            <input type="hidden" name="id_peminjaman" value="<?= IdObfuscator::encode($p['id_peminjaman']); ?>">
                                            <button type="button" class="btn btn-primary btn-sm shadow-sm"
                                                onclick="konfirmasiAksi('formAccKalab', 'Setujui Peminjaman?', 'Yakin setujui?', 'question')">
                                                <i class="fas fa-check mr-1"></i> Setujui
                                            </button>
                                        </form>
                                    <?php elseif ($p['validasi_kalab'] == '1') : ?>
                                        <span class="badge badge-success"><i class="fas fa-check"></i> Selesai</span>
                                    <?php else : ?>
                                        <span class="badge badge-light text-muted">Menunggu</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="step-card">
                                <div class="step-icon <?= ($p['validasi_laboran'] == '1') ? 'step-success' : (($p['validasi_kalab'] == '1') ? 'step-active' : 'step-pending'); ?>">
                                    <?= ($p['validasi_laboran'] == '1') ? '<i class="fas fa-check"></i>' : '2'; ?>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="font-weight-bold mb-0 text-gray-800">Laboran</h6>
                                    <small>Fatimah Azzahrah</small>
                                </div>

                                <div>
                                    <?php if ($role_login == '2' && $p['validasi_laboran'] == '0') : ?>

                                        <?php if ($p['validasi_kalab'] == '1') : ?>
                                            <a href="<?= BASEURL; ?>ValidasiPeminjaman/viewValidasiPosisi/<?= IdObfuscator::encode($p['id_peminjaman']); ?>"
                                                class="btn btn-success btn-sm shadow-sm">
                                                <i class="fas fa-pen-nib mr-1"></i> Tanda Tangan
                                            </a>
                                        <?php else : ?>
                                            <button class="btn btn-secondary btn-sm" disabled style="cursor: not-allowed; opacity: 0.7;">
                                                <i class="fas fa-lock"></i> Terkunci
                                            </button>
                                        <?php endif; ?>

                                    <?php elseif ($p['validasi_laboran'] == '1') : ?>
                                        <span class="badge badge-success"><i class="fas fa-check"></i> Selesai</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <hr>
                            <button type="button" class="btn btn-danger btn-block" onclick="bukaFormTolak('formTolakContainer')">
                                <i class="fas fa-times mr-2"></i>Tolak Peminjaman
                            </button>

                        </div>
                    </div>

                <?php elseif ($status_sekarang == 'disetujui') : ?>
                    <div class="card shadow mb-4 border-left-success">
                        <div class="card-body">
                            <h5 class="text-success font-weight-bold"><i class="fas fa-check-circle"></i> Sedang Dipinjam</h5>
                            <p class="mb-3">Barang sudah diambil. Tunggu pengembalian.</p>

                            <form action="<?= BASEURL; ?>ValidasiPeminjaman/updateStatus" method="post" class="mb-2">
                                <input type="hidden" name="id_peminjaman" value="<?= IdObfuscator::encode($p['id_peminjaman']); ?>">
                                <input type="hidden" name="status" value="dikembalikan">
                                <button type="submit" class="btn btn-primary btn-block shadow-sm" onclick="return confirm('Yakin barang sudah dikembalikan lengkap?')">
                                    <i class="fas fa-box-open mr-2"></i>Terima Pengembalian
                                </button>
                            </form>

                            <button type="button" class="btn btn-outline-danger btn-block btn-sm" onclick="bukaFormTolak('formTolakPengembalianContainer')">
                                <i class="fas fa-exclamation-triangle mr-1"></i> Lapor Masalah
                            </button>
                        </div>
                    </div>

                <?php elseif ($status_sekarang == 'ditolak') : ?>
                    <div class="card shadow mb-4 border-left-danger">
                        <div class="card-body">
                            <h5 class="text-danger font-weight-bold"><i class="fas fa-times-circle"></i> Ditolak</h5>
                            <p class="mb-0"><strong>Alasan:</strong> <?= !empty($p['alasan_penolakan']) ? $p['alasan_penolakan'] : '-'; ?></p>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>

        <div id="formTolakContainer" class="card shadow mb-4 border-bottom-danger form-section-hidden">
            <div class="card-header py-3 bg-danger text-white">
                <h6 class="m-0 font-weight-bold">Form Penolakan</h6>
            </div>
            <div class="card-body">
                <form action="<?= BASEURL; ?>ValidasiPeminjaman/updateStatus" method="post">
                    <input type="hidden" name="id_peminjaman" value="<?= IdObfuscator::encode($p['id_peminjaman']); ?>">
                    <input type="hidden" name="status" value="ditolak">
                    <div class="form-group">
                        <label>Alasan Penolakan:</label>
                        <textarea class="form-control" name="pesan_penolakan" required rows="3" placeholder="Contoh: Jadwal bentrok..."></textarea>
                    </div>
                    <div class="text-right">
                        <button type="button" class="btn btn-secondary mr-2" onclick="tutupForm('formTolakContainer')">Batal</button>
                        <button type="submit" class="btn btn-danger">Kirim Penolakan</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="formTolakPengembalianContainer" class="card shadow mb-4 border-bottom-danger form-section-hidden">
            <div class="card-header py-3 bg-danger text-white">
                <h6 class="m-0 font-weight-bold">Lapor Masalah Pengembalian</h6>
            </div>
            <div class="card-body">
                <form action="<?= BASEURL; ?>ValidasiPeminjaman/tolakPengembalian" method="post">
                    <input type="hidden" name="id_peminjaman" value="<?= IdObfuscator::encode($p['id_peminjaman']); ?>">
                    <div class="form-group">
                        <label>Detail Masalah (Rusak/Hilang):</label>
                        <textarea class="form-control" name="alasan_penolakan" required rows="3"></textarea>
                    </div>
                    <div class="text-right">
                        <button type="button" class="btn btn-secondary mr-2" onclick="tutupForm('formTolakPengembalianContainer')">Batal</button>
                        <button type="submit" class="btn btn-danger">Simpan Laporan</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="mb-4">
            <a href="<?= BASEURL; ?>ValidasiPeminjaman" class="btn btn-secondary btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-arrow-left"></i></span>
                <span class="text">Kembali ke Daftar</span>
            </a>
        </div>

    </div>
</div>

<script>
    function bukaFormTolak(id) {
        // Tutup semua form dulu
        document.getElementById('formTolakContainer').style.display = 'none';
        document.getElementById('formTolakPengembalianContainer').style.display = 'none';

        var el = document.getElementById(id);
        if (el) {
            el.style.display = 'block';
            el.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }
    }

    function tutupForm(id) {
        var el = document.getElementById(id);
        if (el) el.style.display = 'none';
    }

    function konfirmasiAksi(formId, judul, pesan, ikon) {
        Swal.fire({
            title: judul,
            text: pesan,
            icon: ikon, // 'warning', 'error', 'success', 'info', 'question'
            showCancelButton: true,
            confirmButtonColor: '#4e73df', // Warna biru tema template
            cancelButtonColor: '#e74a3b', // Warna merah tema template
            confirmButtonText: 'Ya, Lanjutkan!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika user klik Ya, submit form sesuai ID yang dikirim
                document.getElementById(formId).submit();
            }
        });
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>