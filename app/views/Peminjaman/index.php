<?php
if (!isset($_SESSION['login'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}
?>
<!-- modal keluar -->
<div class="modal fade" id="konfirmasiKeluar" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 15px;">
            <div class="modal-body"
                style="display: flex;justify-content: center; flex-direction: column; align-items: center;">
                <lottie-player src="https://lottie.host/48c004f8-57cd-4acb-a04a-de46793ba7dc/jUGVFL9qIO.json"
                    background="##FFFFFF" speed="1" style="width: 250px; height: 250px" loop autoplay direction="1"
                    mode="normal"></lottie-player>
                <p style="color:#385161; opacity: 0.6; font-weight: 500; font-size: medium;">Apakah anda yakin ingin
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
        <h3 id="title">Peminjaman</h3>
        <div class="flash" style="width: 40%; margin-left:15px;">
            <?php Flasher::flash(); ?>
        </div>

        <!-- Button trigger modal -->
        <div class="btn-fitur" style="display: flex; justify-content: left; gap: 20px; ">
            <button data-toggle="modal" class="btn btn-primary tombolTambahData" data-toggle="modal"
                data-target="#exampleModal" style=" box-shadow: 5px 5px 10px 0px rgba(0, 0, 0, 0.5);">
                <i cl ass="fa-solid fa-plus" style="color: #ffffff"></i> Tambah
            </button>
            <!-- Form diposisikan di samping "Show Entries" -->
            <form method="POST" action="" style="display: flex; justify-content: space-between; gap: 20px; ">
    <select name="sub_barang" id="sub_barang" onchange="this.form.submit()" style="background: #fff; color: #0d1a4a; border: none; padding: 10px;
                font-size: 16px; border-radius: 6px; cursor: pointer;
                box-shadow: 4px 4px 10px rgba(12, 23, 64, 0.5); outline: none;">
        <option value="">Pilih Jenis Barang</option>
        <?php foreach ($data['sub_barang'] ?? [] as $sub): ?>
            <option value="<?= $sub['id_jenis_barang'] ?>" <?= isset($_SESSION['selected_sub_barang']) && $_SESSION['selected_sub_barang'] == $sub['id_jenis_barang'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($sub['sub_barang']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <!-- Filter status yang sesuai dengan nilai enum -->
    <select name="status" id="status" onchange="this.form.submit()" style="background: #fff; color: #0d1a4a; border: none; padding: 10px;
                font-size: 16px; border-radius: 6px; cursor: pointer;
                box-shadow: 4px 4px 10px rgba(12, 23, 64, 0.5); outline: none;">
        <option value="">Pilih Status</option>
        <option value="diproses" <?= isset($_SESSION['selected_status']) && $_SESSION['selected_status'] == 'diproses' ? 'selected' : '' ?>>Diproses</option>
        <option value="disetujui" <?= isset($_SESSION['selected_status']) && $_SESSION['selected_status'] == 'disetujui' ? 'selected' : '' ?>>Disetujui</option>
        <option value="ditolak" <?= isset($_SESSION['selected_status']) && $_SESSION['selected_status'] == 'ditolak' ? 'selected' : '' ?>>Ditolak</option>
    </select>
</form>

        </div>


        <div
            style="max-height: 400px; overflow-y:auto; box-shadow: 5px 5px 10px 0px rgba(0, 0, 0, 0.5); border-radius:5px; padding: 15px ; padding-top:0;">
            <div
                style="height: 80px; display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; position: sticky; top: 0; margin-top: 0; background-color: #fff; z-index: 10;">
                <!-- Dropdown datatables_length dan form dalam satu flex container -->
                <div style="display: flex; align-items: center;">
                    <div class="dataTables_length" style="display: flex; align-items: center; font-size: 14px;">
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
                </div>

                <!-- Div pencarian -->
                <div style="display: flex; align-items: center; justify-content: flex-end; 
        box-shadow: 5px 5px 10px 0px rgba(0, 0, 0, 0.5); border-radius: 8px; 
        overflow: hidden; width: 320px;">
                    <button style="background-color: #0d1a4a; border: none; width: 40px; height: 40px; 
            display: flex; align-items: center; justify-content: center; cursor: pointer; 
            border-radius: 4px 0 0 4px;">
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
                        <th scope="col" class="p-2">No.</th>
                        <th scope="col" class="p-2">Nama Peminjam</th>
                        <!-- <th scope="col" class="p-2">Judul Kegiatan</th> -->
                        <!-- <th scope="col" class="p-2">Tanggal Pengajuan</th> -->
                        <th scope="col" class="p-2">Tanggal Mulai Peminjaman</th>
                        <th scope="col" class="p-2">Tanggal Pengembalian</th>
                        <th scope="col" class="p-2">Jenis Barang</th>
                        <!-- <th scope="col" class="p-2">Jumlah Peminjaman</th> -->
                        <!-- <th scope="col" class="p-2">Keterangan</th> -->
                        <th scope="col" class="p-2">Status</th> <!-- Kolom status -->
                        <th scope="col" class="p-2">Aksi</th> <!-- Kolom aksi -->
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($data['peminjaman'] as $peminjaman): ?>
                        <tr data-id="<?= $peminjaman['id_peminjaman'] ?>" style="cursor: pointer;">
                            <td><?= $no++ ?></td>
                            <td><?= $peminjaman['nama_peminjam'] ?></td>
                            <td><?= date('d-m-Y', strtotime($peminjaman['tanggal_peminjaman'])) ?></td>
                            <td><?= date('d-m-Y', strtotime($peminjaman['tanggal_pengembalian'])) ?></td>
                            <td><?= $peminjaman['sub_barang']; ?></td>
                            <td><?= $peminjaman['status'] ?></td>
                            <td style="display: flex; justify-content: center; align-items: center; gap: 10px;">
                            <?php if (isset($_SESSION['login']) && ($_SESSION['id_role'] == '1' || $_SESSION['id_role'] == '2' || $_SESSION['id_role'] == '3' || $_SESSION['id_role'] == '4')): ?>

                                <!-- Aksi: Edit dan Hapus -->
                                <a href="<?= BASEURL; ?>/Peminjaman/ubahPeminjaman/<?= $peminjaman['id_peminjaman']; ?>"
                                    class="btn d-flex align-items-center justify-content-center tampilModalPeminjaman"
                                    data-toggle="modal" data-target="#exampleModal"
                                    data-id="<?= $peminjaman['id_peminjaman']; ?>">
                                    <i class="fa-solid fa-pen-to-square fa-lg" style="color: #30cc30;"></i>
                                </a>
                                <a class="btn d-flex align-items-center justify-content-center" data-toggle="modal"
                                    data-target="#konfirmasiHapus<?= $peminjaman['id_peminjaman']; ?>">
                                    <i class="fa-solid fa-trash-can fa-lg" style="color: #cc3030;"></i>
                                </a>
                                <div class="modal fade" id="konfirmasiHapus<?= $peminjaman['id_peminjaman']; ?>"
                                    tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content" style="border-radius: 15px;">
                                            <div class="modal-body"
                                                style="display: flex;justify-content: center; flex-direction: column; align-items: center;">
                                                <lottie-player
                                                    src="https://lottie.host/482b772b-9f0c-4065-b54d-dcc81da3b212/Dmb3I1o98u.json"
                                                    background="##FFFFFF" speed="1" style="width: 250px; height: 250px" loop
                                                    autoplay direction="1" mode="normal"></lottie-player>
                                                <p
                                                    style="color:#385161; opacity: 0.6; font-weight: 500; font-size: medium;">
                                                    Apakah anda yakin ingin menghapus item ini?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" style="width: 100px;"
                                                    data-dismiss="modal">Batal</button>
                                                <button type="button" style="width: 100px;" class="btn btn-danger"
                                                    onclick="location.href='<?= BASEURL; ?>Peminjaman/hapusPeminjaman/<?= $peminjaman['id_peminjaman']; ?>'">Hapus</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <a href="<?= BASEURL; ?>Peminjaman/detail/<?= $peminjaman['id_peminjaman']; ?>"
                                    data-toggle="modal" data-target="#modalPeminjaman<?= $peminjaman['id_peminjaman']; ?>"
                                    class="btn d-flex align-items-center justify-content-center">
                                    <i class="fa-solid fa-circle-info fa-lg " style="color: #1250ba;"></i>
                                </a>
                            </td>
                        </tr>

                        <!-- Modal Detail Peminjaman -->
                        <div class="modal fade" id="modalPeminjaman<?= $peminjaman['id_peminjaman']; ?>" tabindex="-1"
                            role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content" style="width: 700px;">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLongTitle" style="font-weight: 600;">Detail
                                            Peminjaman
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
                                                <p><?= $peminjaman['nama_peminjam']; ?></p>
                                            </span>
                                            <span>
                                                <h6>Judul Kegiatan</h6>
                                                <p style="text-transform: capitalize;">
                                                    <?= $peminjaman['judul_kegiatan']; ?>
                                                </p>
                                            </span>
                                            <span>
                                                <h6>Tanggal Pengajuan</h6>
                                                <p style="text-transform: capitalize;">
                                                    <?= $peminjaman['tanggal_pengajuan']; ?>
                                                </p>
                                            </span>
                                            <span>
                                                <h6>Tanggal Mulai Peminjaman</h6>
                                                <p style="text-transform: capitalize;">
                                                    <?= $peminjaman['tanggal_peminjaman']; ?>
                                                </p>
                                            </span>
                                            <span>
                                                <h6>Tanggal Pengembalian</h6>
                                                <p><?= $peminjaman['tanggal_pengembalian']; ?></p>
                                            </span>
                                        </div>
                                        <div style="width: 50%;">
                                            <br><br>
                                            <span>
                                                <h6>Jenis Barang</h6>
                                                <p><?= $peminjaman['sub_barang']; ?></p>
                                            </span>
                                            <span>
                                                <h6>Jumlah Peminjaman</h6>
                                                <p><?= $peminjaman['jumlah_peminjaman']; ?></p>
                                            </span>
                                            <span>
                                                <h6>Keterangan </h6>
                                                <p><?= $peminjaman['keterangan_peminjaman']; ?></p>
                                            </span>
                                            <span>
                                                <h6>Status </h6>
                                                <p><?= $peminjaman['status']; ?></p>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>


        <!-- Modal -->
        <div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog"
            aria-labelledby="tambahPeminjaman" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tambahPeminjaman">Tambah Data Peminjaman</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="<?= BASEURL ?>/peminjaman/tambahPeminjaman" method="post">
                            <input type="hidden" name="id_peminjaman" id="id_peminjaman">
                            <div class="form_peminjaman">
                                <div class="content-modal"
                                    style="display: flex; justify-content: space-around; gap: 20px;">
                                    <div class="sisi_kiri" style="margin: 0px 0px 0px 50px;">
                                        <div class="judul_kegiatan" style="margin-top: 30px;">
                                            <label for="judul_kegiatan">Judul Kegiatan</label><br>
                                            <input type="text" name="judul_kegiatan" id="judul_kegiatan"
                                                style="width: 250px; height: 35px; box-shadow: 0px 5px 5px 0px rgba(0, 0, 0, 0.2);">
                                        </div>
                                        <div class="nama_peminjam" style="margin-top: 30px;">
                                            <label for="nama_peminjam">Nama Peminjam</label><br>
                                            <input type="text" name="nama_peminjam" id="nama_peminjam"
                                                style="width: 250px; height: 35px; box-shadow: 0px 5px 5px 0px rgba(0, 0, 0, 0.2);">
                                        </div>
                                        <div class="tanggal_pengajuan" style="margin-top: 30px;">
                                            <label for="tanggal_pengajuan">Tanggal Pengajuan</label><br>
                                            <input type="date" name="tanggal_pengajuan" id="tanggal_pengajuan"
                                                value="<?= date('Y-m-d'); ?>" readonly
                                                style="width: 250px; height: 35px; box-shadow: 0px 5px 5px 0px rgba(0, 0, 0, 0.2);">
                                        </div>
                                        <div class="tanggal_peminjaman" style="margin-top: 30px;">
                                            <label for="tanggal_peminjaman">Mulai Dari Tanggal</label><br>
                                            <input type="date" name="tanggal_peminjaman" id="tanggal_peminjaman"
                                                style="width: 250px; height: 35px; box-shadow: 0px 5px 5px 0px rgba(0, 0, 0, 0.2);">
                                        </div>
                                    </div>
                                    <div class="sisi_kanan"
                                        style="margin: 0px 80px 0px 0px; display: flex; flex-direction: column; align-items: center;">
                                        <img src="<?= BASEURL ?>img/happy robot assistant.svg" alt=""
                                            width="140px height: 40px;">
                                        <div class="tanggal_pengembalian">
                                            <label for="tanggal_pengembalian" style="margin-top: 55px;">Sampai
                                                Tanggal</label><br>
                                            <input type="date" name="tanggal_pengembalian" id="tanggal_pengembalian"
                                                style="width: 250px; height: 35px; box-shadow: 0px 5px 5px 0px rgba(0, 0, 0, 0.2);">
                                        </div>
                                        <div class="jenis_barang" style="margin-top: 30px;">
                                            <label for="id_jenis_barang">Jenis Barang</label><br>
                                            <select name="id_jenis_barang" class="jenis_barang_select"
                                                style="height: 35px; padding-left: 0px; width: 250px; border-radius: 8px; background-color: rgba(202, 214, 255, 0.4); border: none; padding-left: 15px; box-shadow: 0px 5px 5px 0px rgba(0, 0, 0, 0.2);"
                                                required>
                                                <option>-- Pilih --</option>
                                                <?php foreach ($data['sub_barang'] as $option) { ?>
                                                    <option value="<?php echo $option['id_jenis_barang'] ?>">
                                                        <?php echo $option['sub_barang'] ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="sisi_bawah" style="display: flex; flex-direction: column; gap: 20px;">
                                    <div class="input-group"
                                        style="display: flex; gap: 20px; align-items: center; justify-content: center; margin-top: 30px; margin-left: 5px; ">
                                        <div class="status"">
                                            <label for=" status">Status</label><br>
                                            <select name="status" class="status_select"
                                                style="height: 35px; padding-left: 0px; width: 250px; border-radius: 8px; background-color: rgba(202, 214, 255, 0.4); border: none; padding-left: 15px; box-shadow: 0px 5px 5px 0px rgba(0, 0, 0, 0.2);"
                                                required>
                                                <option>-- Pilih --</option>
                                                <?php
                                                $status_options = ["diproses", "disetujui", "ditolak"];
                                                $selected_status = $data['status'] ?? ''; // Ambil status dari database
                                                foreach ($status_options as $status) {
                                                    $selected = ($selected_status == $status) ? 'selected' : '';
                                                    echo "<option value='$status' $selected>$status</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="jumlah_peminjaman" style="margin-left: 15%;">
                                            <label for="jumlah_peminjaman">Jumlah</label><br>
                                            <input type="number" id="jumlah_peminjaman" name="jumlah_peminjaman"
                                                class="jumlah_input"
                                                style="width: 80px; height: 35px; position: relative; border-radius: 8px; background-color: rgba(202, 214, 255, 0.4); border: none; padding-left: 10px; box-shadow: 0px 5px 5px 0px rgba(0, 0, 0, 0.2);"
                                                min="1">
                                        </div>
                                        <div class="keterangan_peminjaman">
                                            <label for="keterangan_peminjaman">Keterangan</label><br>
                                            <input type="text" id="keterangan_peminjaman" name="keterangan_peminjaman"
                                                class="keterangan_input"
                                                style="width: 250px; position: relative; height: 35px; border-radius: 8px; background-color: rgba(202, 214, 255, 0.4); border: none; padding-left: 10px; box-shadow: 0px 5px 5px 0px rgba(0, 0, 0, 0.2);">
                                        </div>

                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn-kirim-peminjaman" style="
                                color: white;
                                background-color: #0c1740;
                                border: none;
                                border-radius: 8px;
                                margin: 40px 40px;
                                float: right;
                                height: 35px;
                                width: 180px;
                                font-size: 15px;
                                font-weight: 400;
                                box-shadow: 0px 5px 5px 0px rgba(0, 0, 0, 0.2);
                                ">kirim</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>