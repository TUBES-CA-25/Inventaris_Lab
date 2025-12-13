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
        <h1 id="title">Detail Barang</h1>
        <div class="flash" style="width: 40%; margin-left:15px;">
            <?php Flasher::flash(); ?>
        </div>
        <div class="btn-fitur" style="display: flex; justify-content:space-between;">
            <div style="display: flex; justify-content: left; gap: 20px; ">
                <form id="formCheckbox" method="POST" action="<?= BASEURL ?>DetailBarang/cetak">
                    <input type="hidden" id="idbarang" name="id_barang" value="">
                    <button type="submit" style="box-shadow: 5px 5px 10px 0px rgba(0, 0, 0, 0.5);">
                        <i class="fa-solid fa-file-export" style="color: #ffffff;"></i> Ekspor
                    </button>
                </form>



                <?php
                if (isset($_SESSION['login']) && ($_SESSION['id_role'] == '1' || $_SESSION['id_role'] == '2' || $_SESSION['id_role'] == '3' || $_SESSION['id_role'] == '4')) {
                    echo '<button data-toggle="modal" class="btn-tambah-barang" data-target="#modalTambah" style="box-shadow: 5px 5px 10px 0px rgba(0, 0, 0, 0.5);">
                                <i class="fa-solid fa-plus" style="color: #ffffff; "></i> Tambah
                                </button>';
                }
                ?>

                <form method="POST" action="" style="display: flex; justify-content: left; gap: 20px; ">
                    <select name="lokasi" id="lokasi" onchange="this.form.submit()" style="background: #fff; color: #0d1a4a; border: none; padding: 10px;
       font-size: 16px; border-radius: 6px; cursor: pointer;
       box-shadow: 4px 4px 10px rgba(12, 23, 64, 0.5); outline: none;">
                        <option value="">Pilih Lokasi</option>
                        <?php foreach ($data['lokasiPenyimpanan'] ?? [] as $lokasi): ?>
                            <option value="<?= $lokasi['id_lokasi_penyimpanan'] ?>" <?= isset($_POST['lokasi']) && $_POST['lokasi'] == $lokasi['id_lokasi_penyimpanan'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($lokasi['nama_lokasi_penyimpanan']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <select name="sub_barang" id="sub_barang" onchange="this.form.submit()" style="background: #fff; color: #0d1a4a; border: none; padding: 10px;
       font-size: 16px; border-radius: 6px; cursor: pointer;
       box-shadow: 4px 4px 10px rgba(12, 23, 64, 0.5); outline: none;">
                        <option value="">Pilih Sub Barang</option>
                        <?php foreach ($data['sub_barang'] ?? [] as $sub): ?>
                            <option value="<?= $sub['id_jenis_barang'] ?>" <?= isset($_POST['sub_barang']) && $_POST['sub_barang'] == $sub['id_jenis_barang'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($sub['sub_barang']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <select name="merek_barang" id="merek_barang" onchange="this.form.submit()" style="background: #fff; color: #0d1a4a; border: none; padding: 10px;
       font-size: 16px; border-radius: 6px; cursor: pointer;
       box-shadow: 4px 4px 10px rgba(12, 23, 64, 0.5); outline: none;">
                        <option value="">Pilih Merek Barang</option>
                        <?php foreach ($data['nama_merek_barang'] ?? [] as $merek): ?>
                            <option value="<?= $merek['id_merek_barang'] ?>" <?= isset($_POST['merek_barang']) && $_POST['merek_barang'] == $merek['id_merek_barang'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($merek['nama_merek_barang']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>

            </div>
            <!-- <div style="display: flex; align-items: center; gap:10px; justify-content: end;">
                <input type="checkbox" class="checkbox" id="selectAllCheckbox" name="selectAllCheckbox"
                    style="width: 15px;">
                <label class="checkbox" for="selectAllCheckbox" style="margin-top: 7px;">Pilih semua</label>
            </div> -->
        </div>
        <div
            style="max-height: 400px; overflow-y:auto; box-shadow: 5px 5px 10px 0px rgba(0, 0, 0, 0.5); border-radius:5px; padding: 15px ; padding-top:0;">
            <div
                style=" height: 80px; display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; position: sticky; top: 0;margin-top: 0; background-color: #fff; z-index: 10; ">
                <!-- Dropdown datatables_length -->
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



                <!-- Div pencarian -->
                <div
                    style="display: flex; align-items: center; justify-content: flex-end; box-shadow: 5px 5px 10px 0px rgba(0, 0, 0, 0.5); border-radius: 8px; overflow: hidden; width: 320px;">
                    <!-- Tombol Pencarian -->
                    <button
                        style="background-color: #0d1a4a; border: none; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; cursor: pointer; border-radius: 4px 0 0 4px;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" width="20" height="20">
                            <path
                                d="M10 2a8 8 0 016.32 12.9l5.38 5.38a1 1 0 01-1.42 1.42l-5.38-5.38A8 8 0 1110 2zm0 2a6 6 0 100 12 6 6 0 000-12z">
                            </path>
                        </svg>
                    </button>
                    <!-- Input Pencarian -->
                    <input type="text" id="customSearch" class="form-control" placeholder="Cari"
                        style="border: none; outline: none; padding: 10px 15px; font-size: 16px; flex-grow: 1; height: 40px;">
                </div>
            </div>
            <table id="myTable" class="table table-hover table-sm" style=" width:100%;">
                <thead class="table-info">
                    <tr>
                        <th scope="col" class="p-2">No.</th>
                        <th scope="col" class="p-2">Foto</th>
                        <th scope="col" class="p-2">Kode barang</th>
                        <th scope="col" class="p-2">Sub barang</th>
                        <th scope="col" class="p-2">Merek barang</th>
                        <th scope="col" class="p-2">Kondisi barang</th>
                        <th scope="col" class="p-2">Status</th>
                        <th scope="col" class="p-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <form action="<?= BASEURL ?>DetailBarang/cetak" method="post" id="formCheckbox">
                        <?php $i = 1; ?>
                        <?php foreach ($data['dataTampilBarang'] as $row): ?>
                            <tr style="font-size: 14px;">
                                <td scope="row" class="p-3"><?= $i++; ?></td>
                                <td class="p-3"><img src="<?= BASEURL . $row['foto_barang']; ?>" alt=""
                                        style="width:60px; height:60px;"></td>
                                <td class="p-3"><?= $row['kode_barang']; ?></td>
                                <td class="p-3" style="text-transform: capitalize;"><?= $row['sub_barang']; ?></td>
                                <td class="p-3" style="text-transform: capitalize;"><?= $row['nama_merek_barang']; ?></td>
                                <td class="p-3"><?= $row['kondisi_barang']; ?></td>
                                <td class="p-3"><?= $row['status']; ?></td>
                                <td class="p-3" style="display: flex;">
                                    <?php if (isset($_SESSION['login']) && ($_SESSION['id_role'] == '1' || $_SESSION['id_role'] == '2' || $_SESSION['id_role'] == '3' || $_SESSION['id_role'] == '4')): ?>
                                        <!-- Hapus -->
                                        <a class="btn d-flex align-items-center justify-content-center" data-toggle="modal"
                                            id="iconHapusBarang" data-target="#konfirmasiHapus<?= $row['id_barang'] ?>"
                                            data-id="<?= $row['id_barang']; ?>">
                                            <i class="fa-solid fa-trash-can fa-lg" style="color: #cc3030;"></i>
                                        </a>
                                        <!-- Ubah -->
                                        <a href="<?= BASEURL ?>DetailBarang/getUbah/<?= $row['id_barang'] ?>"
                                            class="btn d-flex align-items-center justify-content-center tampilBarangUbah"
                                            data-toggle="modal" data-target="#modalTambah" data-id="<?= $row['id_barang']; ?>">
                                            <i class="fa-solid fa-pen-to-square fa-lg" style="color: #30cc30;"></i>
                                        </a>
                                    <?php endif; ?>
                                    <!-- detail -->
                                    <a href="<?= BASEURL; ?>DetailBarang/detail/<?= $row['id_barang']; ?>"
                                        data-toggle="modal" data-target="#modalDetail<?= $row['id_barang']; ?>"
                                        class="btn d-flex align-items-center justify-content-center">
                                        <i class="fa-solid fa-circle-info fa-lg " style="color: #1250ba;"></i>
                                    </a>
                                    <input class="checkbox" type="checkbox" id="checkbox" onclick="tampilCetak()"
                                        name="id_barang[]" value="<?= $row['id_barang'] ?>" style="width:15px">
                                    <div class="modal fade" id="konfirmasiHapus<?= $row['id_barang'] ?>" tabindex="-1"
                                        role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content" style="border-radius: 15px;">
                                                <div class="modal-body"
                                                    style="display: flex;justify-content: center; flex-direction: column; align-items: center;">
                                                    <lottie-player
                                                        src="https://lottie.host/482b772b-9f0c-4065-b54d-dcc81da3b212/Dmb3I1o98u.json"
                                                        background="##FFFFFF" speed="1" style="width: 250px; height: 250px"
                                                        loop autoplay direction="1" mode="normal"></lottie-player>
                                                    <p
                                                        style="color:#385161; opacity: 0.6; font-weight: 500; font-size: medium;">
                                                        Apakah anda
                                                        yakin ingin menghapus item ini?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" style="width: 100px;"
                                                        data-dismiss="modal">Batal</button>
                                                    <button type="button" style="width: 100px;" class="btn btn-danger"
                                                        id="konfirmasiHapusBarang"
                                                        onclick="location.href='<?= BASEURL ?>DetailBarang/hapus/<?= $row['id_barang'] ?>'">Hapus</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="modalDetail<?= $row['id_barang']; ?>" tabindex="-1"
                                        role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content" style="width: 700px;">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLongTitle"
                                                        style="font-weight: 600;">Detail barang
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body"
                                                    style="display: flex; gap:50px; font-weight: 500; width:100%;">
                                                    <style>
                                                        span p {
                                                            word-wrap: break-word;
                                                            opacity: 0.5;
                                                        }
                                                    </style>
                                                    <div style="width: 50%;">
                                                        <span>
                                                            <h6>Foto</h6>
                                                            <img src="<?= BASEURL . $row['foto_barang']; ?>" alt=""
                                                                style="width:150px; height:150px;">
                                                        </span>
                                                        <span>
                                                            <h6>Kode barang</h6>
                                                            <p><?= $row['kode_barang']; ?></p>
                                                        </span>
                                                        <span>
                                                            <h6>Jenis barang</h6>
                                                            <p style="text-transform: capitalize;">
                                                                <?= $row['sub_barang']; ?>
                                                            </p>
                                                        </span>
                                                        <span>
                                                            <h6>Merek barang</h6>
                                                            <p style="text-transform: capitalize;">
                                                                <?= $row['nama_merek_barang']; ?>
                                                            </p>
                                                        </span>
                                                        <span>
                                                            <h6>Deskripsi Barang</h6>
                                                            <p style="text-transform: capitalize;">
                                                                <?= $row['deskripsi_barang']; ?>
                                                            </p>
                                                        </span>
                                                        <span>
                                                            <h6>Jumlah barang</h6>
                                                            <p><?= $row['jumlah_barang']; ?></p>
                                                        </span>
                                                        <span>
                                                            <h6>Satuan jumlah</h6>
                                                            <p><?= $row['nama_satuan']; ?></p>
                                                        </span>
                                                        <span>
                                                            <h6>Status pinjam</h6>
                                                            <p><?= $row['status_peminjaman']; ?></p>
                                                        </span>
                                                    </div>
                                                    <div style="width: 50%;">
                                                        <span>
                                                            <h6>Qr code</h6>
                                                            <div style="display: flex; flex-direction: column;">
                                                                <img src="<?= BASEURL . $row['qr_code'] ?>" alt=""
                                                                    style="width:200px; height:200px;">
                                                                <a href="<?= BASEURL . $row['qr_code'] ?>"
                                                                    style="margin-left: 15px;" download>Download</a>
                                                            </div>
                                                        </span>
                                                        <br><br>
                                                        <span>
                                                            <h6>Tanggal pengadaan</h6>
                                                            <p><?= $row['tgl_pengadaan_barang']; ?></p>
                                                        </span>
                                                        <span>
                                                            <h6>Lokasi penyimpanan</h6>
                                                            <p><?= $row['nama_lokasi_penyimpanan']; ?></p>
                                                        </span>
                                                        <span style="width: 100%;">
                                                            <h6>Detail lokasi penyimpanan</h6>
                                                            <p style="text-transform: capitalize;">
                                                                <?= $row['deskripsi_detail_lokasi']; ?>
                                                            </p>
                                                        </span>
                                                        <span>
                                                            <h6>Kondisi barang</h6>
                                                            <p><?= $row['kondisi_barang']; ?></p>
                                                        </span>
                                                        <span>
                                                            <h6>Keterangan label</h6>
                                                            <p><?= $row['keterangan_label']; ?></p>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </form>
                </tbody>
            </table>
        </div>
        <div class="modal fade" id="modalTambah" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content" style="height: 100%;width:900px; border-radius:15px">
                    <div class="modal-header">
                        <h5 class="modal-title" id="title-barang">Tambah Barang</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body body-barang">
                        <form action="<?= BASEURL ?>DetailBarang/tambahBarang" method="post"
                            enctype="multipart/form-data">
                            <input type="hidden" name="id_barang" id="id_barang" value="<?= $row['id_barang'] ?>">
                            <div style="display: flex; width:100%; gap:20%;">
                                <div style="margin-top: 8px;">
                                    <div class="foto_barang">
                                        <input type="file" name="foto_barang" id="foto_barang" accept="image/*"
                                            onchange="limitSize()" placeholder="Pilih foto" />
                                        <label for="foto_barang">Upload Foto (Maks 2 MB) </label>
                                    </div>
                                    <br>
                                    <div class="sub_barang">
                                        <label for="sub_barang">Sub barang</label>
                                        <br>
                                        <select name="sub_barang" id="sub_barang" style="width: 250px;" required>
                                            <option>-- Pilih --</option>
                                            <?php foreach ($data['sub_barang'] as $option) { ?>
                                                <option value="<?php echo $option['id_jenis_barang'] ?>">
                                                    <?php echo $option['sub_barang'] ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <br>
                                    <div class="nama_merek_barang">
                                        <label for="nama_merek_barang">Merek barang</label>
                                        <br>
                                        <select name="nama_merek_barang" id="nama_merek_barang" style="width: 250px;"
                                            required>
                                            <option>-- Pilih --</option>
                                            <?php foreach ($data['nama_merek_barang'] as $option) { ?>
                                                <option value="<?php echo $option['id_merek_barang'] ?>">
                                                    <?php echo $option['nama_merek_barang'] ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <br>
                                    <div class="deskripsi_barang">
                                        <label for="deskripsi_barang">Deskripsi barang</label>
                                        <br>
                                        <input type="text" name="deskripsi_barang" id="deskripsi_barang"
                                            oninput="camelCase()()" style="width: 250px;">
                                    </div>
                                    <br>
                                    <div class="jumlah_barang">
                                        <label for="jumlah_barang">Jumlah barang</label>
                                        <br>
                                        <input type="number" name="jumlah_barang" id="jumlah_barang"
                                            style="width: 250px; text-align: center;" min="1" value="1" readonly
                                            required placeholder="1">
                                    </div>
                                    <br>
                                    <div class="satuan">
                                        <label for="satuan">Satuan</label>
                                        <br>
                                        <select name="satuan" id="satuan" required style="width: 250px;">
                                            <option>-- Pilih --</option>
                                            <?php foreach ($data['satuan'] as $option) { ?>
                                                <option value="<?php echo $option['id_satuan']; ?>">
                                                    <?php echo $option['nama_satuan']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <br>
                                    <div class="barang_ke">
                                        <label for="barang_ke">Barang ke-</label>
                                        <br>
                                        <input type="number" name="barang_ke" id="barang_ke" style="width: 250px;"
                                            min="0" required>
                                    </div>
                                    <br>
                                    <div class="total_barang">
                                        <label for="total_barang">Total barang</label>
                                        <br>
                                        <input type="number" name="total_barang" id="total_barang" style="width: 250px;"
                                            min="0" required>
                                    </div>
                                </div>
                                <div style="margin-top: 8px;">
                                    <div class="tgl_pengadaan_barang">
                                        <label for="tgl_pengadaan_barang">Tgl pengadaan</label>
                                        <br>
                                        <input type="date" name="tgl_pengadaan_barang" id="tgl_pengadaan_barang"
                                            style="width: 250px;" required>
                                    </div>
                                    <br>
                                    <div class="lokasi_penyimpanan">
                                        <label for="lokasi_penyimpanan">Lokasi penyimpanan</label>
                                        <br>
                                        <select name="lokasi_penyimpanan" id="lokasi_penyimpanan" required
                                            style="width: 250px;">
                                            <option>-- Pilih --</option>
                                            <?php foreach ($data['lokasiPenyimpanan'] as $option) { ?>
                                                <option value="<?php echo $option['id_lokasi_penyimpanan']; ?>">
                                                    <?php echo $option['nama_lokasi_penyimpanan']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <br>
                                    <div class="deskripsi_detail_lokasi">
                                        <label for="deskripsi_detail_lokasi">Detail penyimpanan</label>
                                        <br>
                                        <input type="text" name="deskripsi_detail_lokasi" id="deskripsi_detail_lokasi"
                                            style="width: 250px;" oninput="camelCase()">
                                    </div>
                                    <br>
                                    <div class="status" style="margin-top: 10px;">
                                        <label for="status">Status</label>
                                        <br>
                                        <select name="status" id="status" required style="width: 250px;">
                                            <option>-- Pilih --</option>
                                            <?php foreach ($data['status'] as $option) { ?>
                                                <option value="<?php echo $option['id_status']; ?>">
                                                    <?php echo $option['status']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <br>
                                    <div class="kondisi_barang">
                                        <label for="kondisi_barang">Kondisi barang</label>
                                        <br>
                                        <select name="kondisi_barang" id="kondisi_barang" required
                                            style="width: 250px;">
                                            <option>-- Pilih --</option>
                                            <?php foreach ($data['kondisiBarang'] as $option) { ?>
                                                <option value="<?php echo $option['id_kondisi_barang']; ?>">
                                                    <?php echo $option['kondisi_barang']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <br>
                                    <div class="status_pinjam">
                                        <label for="status_pinjam">Status pinjam</label>
                                        <br>
                                        <select name="status_pinjam" id="status_pinjam"
                                            style="width: 250px; margin-top: 5px;">
                                            <option>-- Pilih --</option>
                                            <option value="bisa">Bisa</option>
                                            <option value="tidak bisa">Tidak bisa</option>
                                        </select>
                                    </div>
                                    <br>
                                    <div class="keterangan_label" style="margin-top:5px">
                                        <label for="keterangan_label">Keterangan label</label>
                                        <br>
                                        <select name="keterangan_label" id="keterangan_label" required
                                            style="width: 250px; margin-top: 5px;">
                                            <option>-- Pilih --</option>
                                            <option value="sudah">Sudah</option>
                                            <option value="belum">Belum</option>
                                        </select>
                                    </div>
                                    <br>
                                    <br>
                                    <br>
                                </div>
                            </div>
                            <br>
                            <div class="modal-footer" style="margin-left: 30%;">
                                <div
                                    style="display: flex; width:100%; align-items: end; justify-content:end; margin-top:50px;">
                                    <button type="submit" id="kirim">Kirim</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>