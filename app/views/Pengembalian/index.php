<?php
if (!isset($_SESSION['login'])) {
    header("Location: " . BASEURL . "Login");
    exit;
}
?>
<!-- modal keluar -->
<div class="modal fade" id="konfirmasiKeluar" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 15px;">
            <div class="modal-body"
                style="display: flex; justify-content: center; flex-direction: column; align-items: center;">

                <lottie-player src="https://lottie.host/48c004f8-57cd-4acb-a04a-de46793ba7dc/jUGVFL9qIO.json"
                    background="#FFFFFF" speed="1" style="width: 250px; height: 250px" loop autoplay></lottie-player>
                <p style="color:#385161; opacity: 0.6; font-weight: 500; font-size: medium;">Apakah Anda yakin ingin
                    keluar?</p>
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
                            <tr data-id="<?= $pengembalian['id_pengembalian'] ?? '' ?>" style="cursor: pointer;">
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
                                    <a href="<?= BASEURL; ?>/Pengembalian/ubahPengembalian/<?= $pengembalian['id_pengembalian']; ?>"
                                        class="btn d-flex align-items-center justify-content-center tampilModalPengembalian"
                                        data-toggle="modal" data-target="#modalEditPengembalian"
                                        data-id="<?= $pengembalian['id_pengembalian']; ?>">
                                        <i class="fa-solid fa-pen-to-square fa-lg" style="color: #30cc30;"></i>
                                    </a>
                                    <?php endif; ?>
                                    <a href="<?= BASEURL; ?>Pengembalian/detail/<?= $pengembalian['id_pengembalian']; ?>"
                                        data-toggle="modal"
                                        data-target="#modalPengembalian<?= $pengembalian['id_pengembalian']; ?>"
                                        class="btn d-flex align-items-center justify-content-center">
                                        <i class="fa-solid fa-circle-info fa-lg " style="color: #1250ba;"></i>
                                    </a>
                                </td>
                            </tr>


                            <!-- Modal Detail Pengembalian -->
                            <div class="modal fade" id="modalPengembalian<?= $pengembalian['id_pengembalian']; ?>" tabindex="-1"
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


            <!-- Modal Edit Pengembalian -->
            <div class="modal fade bd-example-modal-lg" id="modalEditPengembalian" tabindex="-1" role="dialog"
                aria-labelledby="editPengembalian" aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document" style="
        border-radius: 10px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        overflow: hidden;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editPengembalian">Edit Data Pengembalian</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" style="padding: 30px;">
                            <form action="<?= BASEURL ?>/pengembalian/editPengembalian" method="post">
                                <input type="hidden" name="id_pengembalian" id="id_pengembalian">
                                <input type="hidden" name="id_peminjaman" id="id_peminjaman">
                                <!-- Layout Nama Peminjam, Tanggal Peminjaman, dsb. di kiri & IMG di kanan -->
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <div style="width: 50%;">
                                        <div class="form-group">
                                            <label for="nama_peminjam">Nama Peminjam</label>
                                            <input type="text" id="nama_peminjam" class="form-control" readonly
                                                style="background-color: #f0f5ff; color: #0c1740; font-weight: bold; border: none; padding: 5px; border-radius: 5px; font-size: 14px; width: 80%;">
                                        </div>
                                        <div class="form-group">
                                            <label for="tanggal_peminjaman">Tanggal Peminjaman</label>
                                            <input type="text" id="tanggal_peminjaman" class="form-control" readonly
                                                style="background-color: #f0f5ff; color: #0c1740; font-weight: bold; border: none; padding: 5px; border-radius: 5px; font-size: 14px; width: 80%;">
                                        </div>
                                        <div class="form-group">
                                            <label for="tanggal_pengembalian">Tanggal Pengembalian</label>
                                            <input type="text" id="tanggal_pengembalian"  name="tanggal_pengembalian" class="form-control" readonly
                                                style="background-color: #f0f5ff; color: #0c1740; font-weight: bold; border: none; padding: 5px; border-radius: 5px; font-size: 14px; width: 80%;">
                                        </div>
                                        <div class="form-group">
                                            <label for="tanggal_sekarang">Tanggal Sekarang</label>
                                            <input type="text" id="tanggal_sekarang" class="form-control" readonly
                                                style="background-color: #f0f5ff; color: #0c1740; font-weight: bold; border: none; padding: 5px; border-radius: 5px; font-size: 14px; width: 80%;"
                                                value="<?= date('d-m-Y'); ?>">
                                        </div>

                                    </div>
                                    <div style="width: 50%; text-align: center;">
                                        <img src="<?= BASEURL ?>img/happy robot assistant.svg"
                                            alt="Happy Robot Assistant" style="width: 250px; height: 250px;">
                                    </div>
                                </div>

                                <!-- Status Pengembalian & Keterangan (Sudah Sesuai) -->
                                <div style="display: flex; gap: 20px;">
                                    <div style="flex: 1;">
                                        <label for="status_pengembalian">Status Pengembalian</label>
                                        <select name="status_pengembalian" id="status_pengembalian" required
                                            style="width: 100%; height: 40px; border-radius: 5px; border: none; padding: 5px 10px; background-color: #f0f5ff; color: #0c1740;">
                                            <option value="">-- Pilih --</option>
                                            <option value="Dikembalikan">Dikembalikan</option>
                                            <option value="Belum Dikembalikan">Belum Dikembalikan</option>
                                            <option value="Rusak">Rusak</option>
                                            <option value="Hilang">Hilang</option>
                                        </select>
                                    </div>
                                    <div style="flex: 1;">
                                        <label for="keterangan">Keterangan</label>
                                        <select name="keterangan" id="keterangan" required readonly
                                            style="width: 100%; height: 40px; border-radius: 5px; border: none; padding: 5px 10px; background-color: #f0f5ff; color: #0c1740; pointer-events: none;">
                                            <option value=""></option>
                                            <option value="Tepat Waktu">Tepat Waktu</option>
                                            <option value="Tidak Tepat Waktu">Tidak Tepat Waktu</option>
                                            <option value="Bermasalah">Bermasalah</option>
                                        </select>
                                    </div>

                                </div>

                                <!-- Detail Masalah -->
                                <div style="margin-top: 20px;">
                                    <label for="detail_masalah">Detail Masalah</label>
                                    <textarea name="detail_masalah" id="detail_masalah" rows="4"
                                        style="width: 100%; border-radius: 5px; padding: 10px; border: none; background-color: #f0f5ff; color: #0c1740;"></textarea>
                                </div>

                                <!-- Tombol Simpan -->
                                <div class="modal-footer" style="justify-content: center;">
                                    <button type="submit" class="btn" style="
                            background-color: #0c1740;
                            color: white;
                            border: none;
                            border-radius: 8px;
                            height: 40px;
                            width: 200px;
                            font-size: 16px;
                            font-weight: bold;
                            cursor: pointer;
                            transition: 0.3s;
                        " onmouseover="this.style.backgroundColor='#162a66'"
                                        onmouseout="this.style.backgroundColor='#0c1740'">
                                        Simpan Perubahan
                                    </button>
                                </div></form>
            </div>
        </div>
    </div>
</div>


    </div>
</div>