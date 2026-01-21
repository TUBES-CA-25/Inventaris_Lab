<?php
if (!isset($_SESSION['login'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}
?>

<div class="container-fluid" style="padding-left: 280px; padding-top: 30px; padding-right: 30px;">
    <div class="card shadow-sm border-0" style="border-radius: 15px; background: white; padding: 25px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">Pengembalian</h3>
            <div class="input-group" style="width: 300px;">
                <input type="text" class="form-control" placeholder="Search...">
                <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" style="width: 100px;" data-dismiss="modal">Batal</button>
                <button type="button" style="width: 100px;" class="btn btn-danger"
                    onclick="location.href='<?= BASEURL; ?>Logout'">Keluar</button>
            </div>
        </div>
    </div>
</div>
<div class="content">
    <div class="content-beranda" style="overflow: hidden;">
        <h3 id="title">Pengembalian</h3>
        <div class="flash" style="width: 40%; margin-left:15px;">
            <?php Flasher::flash(); ?>
        </div>

        <div
            style="max-height: 400px; overflow-y: auto; box-shadow: 5px 5px 10px 0 rgba(0, 0, 0, 0.5); border-radius: 5px; padding: 15px;">
            <div
                style="height: 80px; display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; position: sticky; top: 0; background-color: #fff; z-index: 10;">
                <div class="dataTables_length"
                    style="display: inline-block; font-size: 14px; display: flex; justify-content: space-between; align-items: center;">
                    <label>
                        Show
                        <select name="entries_length" aria-controls="example" class="form-control form-control-sm"
                            style="width: auto; display: inline-block; margin-left: 5px; margin-right: 5px;">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        entries
                    </label>
                </div>

                <div
                    style="display: flex; align-items: center; justify-content: flex-end; box-shadow: 5px 5px 10px 0 rgba(0, 0, 0, 0.5); border-radius: 8px; overflow: hidden; width: 320px;">
                    <button
                        style="background-color: #0d1a4a; border: none; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" width="20" height="20">
                            <path
                                d="M10 2a8 8 0 016.32 12.9l5.38 5.38a1 1 0 01-1.42 1.42l-5.38-5.38A8 8 0 1110 2zm0 2a6 6 0 100 12 6 6 0 000-12z">
                            </path>
                        </svg>
                    </button>
                    <input type="text" id="customSearch" class="form-control" placeholder="Cari"
                        style="border: none; outline: none; padding: 10px 15px; font-size: 16px; flex-grow: 1; height: 40px;">
                </div>
            </div>

            <table id="myTable" class="table table-hover table-sm" style="width:100%;">
                <thead class="table-info">
                    <tr>
                        <th>No.</th>
                        <th>Nama Peminjam</th>
                        <th>Tanggal Peminjaman</th>
                        <th>Tanggal Pengembalian</th>
                        <th>Sub Barang</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                        <th>Detail Masalah</th>
                        <th scope="col" class="p-2">Aksi</th> <!-- Kolom aksi -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    if (!empty($data['pengembalian'])):
                        foreach ($data['pengembalian'] as $pengembalian): ?>
                            <tr data-id="<?= IdObfuscator::encode($pengembalian['id_pengembalian'] ?? '') ?>" style="cursor: pointer;">
                                <td><?= $no++ ?></td>
                                <td><?= $pengembalian['nama_peminjam'] ?></td>
                                <td><?= date('d-m-Y', strtotime($pengembalian['tanggal_peminjaman'])) ?></td>
                                <td><?= date('d-m-Y', strtotime($pengembalian['tanggal_pengembalian'])) ?></td>
                                <td><?= $pengembalian['sub_barang'] ?></td>
                                <td><?= $pengembalian['status_pengembalian'] ?></td>
                                <td><?= $pengembalian['keterangan'] ?? '-' ?></td>
                                <td><?= $pengembalian['detail_masalah'] ?? '-' ?></td>
                                <td style="display: flex; justify-content: center; align-items: center; gap: 10px;">
                                <?php if (isset($_SESSION['login']) && ($_SESSION['id_role'] == '1' || $_SESSION['id_role'] == '2' || $_SESSION['id_role'] == '3' || $_SESSION['id_role'] == '4')): ?>
                                    <a href="<?= BASEURL; ?>/Pengembalian/ubahPengembalian/<?= IdObfuscator::encode($pengembalian['id_pengembalian']); ?>"
                                        class="btn d-flex align-items-center justify-content-center tampilModalPengembalian"
                                        data-toggle="modal" data-target="#modalEditPengembalian"
                                        data-id="<?= IdObfuscator::encode($pengembalian['id_pengembalian']); ?>">
                                        <i class="fa-solid fa-pen-to-square fa-lg" style="color: #30cc30;"></i>
                                    </a>
                                    <?php endif; ?>
                                    <a href="<?= BASEURL; ?>Pengembalian/detail/<?= IdObfuscator::encode($pengembalian['id_pengembalian']); ?>"
                                        data-toggle="modal"
                                        data-target="#modalPengembalian<?= IdObfuscator::encode($pengembalian['id_pengembalian']); ?>"
                                        class="btn d-flex align-items-center justify-content-center">
                                        <i class="fa-solid fa-circle-info fa-lg " style="color: #1250ba;"></i>
                                    </a>
                                </td>
                            </tr>


                            <!-- Modal Detail Pengembalian -->
                            <div class="modal fade" id="modalPengembalian<?= IdObfuscator::encode($pengembalian['id_pengembalian']); ?>" tabindex="-1"
                                role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content" style="width: 700px;">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLongTitle" style="font-weight: 600;">Detail
                                                Pengembalian
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body" style="display: flex; gap:50px; font-weight: 500; width:100%;">
                                            <style>
                                                span p {
                                                    word-wrap: break-word;
                                                    opacity: 0.5;
                                                }
                                            </style>
                                            <div style="width: 50%;">
                                                <span>
                                                    <h6>Nama Peminjam</h6>
                                                    <p><?= $pengembalian['nama_peminjam']; ?></p>
                                                </span>
                                                <span>
                                                    <h6>Tanggal Mulai Peminjaman</h6>
                                                    <p style="text-transform: capitalize;">
                                                        <?= date('d-m-Y', strtotime($pengembalian['tanggal_peminjaman'])) ?>
                                                    </p>
                                                </span>
                                                <span>
                                                    <h6>Tanggal Pengembalian</h6>
                                                    <p><?= date('d-m-Y', strtotime($pengembalian['tanggal_pengembalian'])) ?>
                                                    </p>
                                                </span>
                                            </div>
                                            <div style="width: 50%;">
                                                <span>
                                                    <h6>Jenis Barang</h6>
                                                    <p><?= $pengembalian['sub_barang']; ?></p>
                                                </span>
                                                <span>
                                                    <h6>Status </h6>
                                                    <p><?= $pengembalian['status_pengembalian']; ?></p>
                                                </span>
                                                <span>
                                                    <h6>Keterangan </h6>
                                                    <p><?= $pengembalian['keterangan']; ?></p>
                                                </span>
                                                <span>
                                                    <h6>Detail Masalah</h6>
                                                    <p><?= $pengembalian['detail_masalah']; ?></p>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                       
                            <?php endforeach;
                    endif; ?>
                </tbody>
            </table>

        </div>
        
       <div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead style="background-color: #0d1b3e; color: white;">
            <tr>
                <th class="py-3 ps-3">No</th>
                <th>Judul kegiatan</th>
                <th>Tgl pengajuan</th>
                <th>Tgl mulai peminjaman</th>
                <th>Tgl akhir peminjaman</th>
                <th class="text-center">Status</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; foreach($data['riwayat'] as $r) : ?>
            <tr>
                <td class="ps-3"><?= $i++; ?></td>
                <td><?= $r['judul_kegiatan']; ?></td>
                <td><?= $r['tanggal_pengajuan']; ?></td>
                <td><?= $r['tanggal_peminjaman']; ?></td>
                <td><?= $r['tanggal_pengembalian']; ?></td>
                <td class="text-center">
                    <?php if($r['status'] == 'dikembalikan') : ?>
                        <span class="badge bg-success rounded-pill px-3">dikembalikan</span>
                    <?php else : ?>
                        <span class="badge bg-warning text-dark rounded-pill px-3">dipinjam</span>
                    <?php endif; ?>
                </td>
                <td class="text-center">
                    <?php if($r['status'] == 'dipinjam') : ?>
                    <a href="<?= BASEURL; ?>Pengembalian/input/<?= $r['id_peminjaman']; ?>" class="text-dark me-2" title="Input Pengembalian">
                        <i class="fas fa-edit"></i>
                    </a>
                    <?php endif; ?>

                    <a href="<?= BASEURL; ?>Pengembalian/detail/<?= $r['id_peminjaman']; ?>" class="text-dark" title="Lihat Detail">
                        <i class="fas fa-eye"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
    </div>
</div>