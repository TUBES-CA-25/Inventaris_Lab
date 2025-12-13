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
        <div class="flash" style="width: 40%; margin-left:15px;">
            <?php Flasher::flash(); ?>
        </div>
        <div class="container"
            style="display: flex; justify-content: space-between; align-items: center; width: 100%; height: 100vh; flex-direction: column;">
            <!-- Ucapan Selamat Datang -->
            <div class="kata"
                style="text-align: center; background: #0d1a4a; color: white; padding: 25px;  width: 120%;  height: 10%; ">
                <h2 style="margin-bottom: 0;">Selamat Datang di Sistem Inventori Barang ICLABS</h2>
            </div>
            <div class="box"
                style="width: 100%; height: 90%; display: flex; justify-content:space-between; align-items: center; gap:20px; margin-top: 1px; ">
                <!-- Gambar Figure -->
                <div class="content-figure">
                    <img id="img-figure-daftar" src="<?= BASEURL ?>img/happy robot assistant.svg" alt="figure" />
                    <div class="hello-text">Hello! 👋</div>
                </div>
                <!-- Kotak Data -->
                <div class="box2" style="display: flex; flex-direction: column; align-items: center; gap: 15px; width: 100%; height: max-content; max-width: 600px; margin: auto;">
    <!-- Baris Atas -->
    <div style="display: flex; justify-content: center; gap: 15px; width: 100%; max-width: 500px;">
        <div class="data1 data-box" onclick="location.href='<?= BASEURL ?>JenisBarang'" style="width: 50%; padding: 30px 0; text-align: center;">
            <i class="fa-solid fa-box"></i>
            <p><span style="font-size: 24px; font-weight: bold;"><?= $data['jumlah_jenis_barang']; ?></span> Jenis Barang</p>
        </div>
        <div class="data2 data-box" onclick="location.href='<?= BASEURL ?>peminjaman'" style="width: 50%; padding: 30px 0; text-align: center;">
            <i class="fa-solid fa-receipt"></i>
            <p><span style="font-size: 24px; font-weight: bold;"><?= $data['jumlah_peminjaman']; ?></span> Peminjaman</p>
        </div>
    </div>

    <!-- Baris Bawah -->
    <div style="display: flex; justify-content: center; gap: 15px; width: 100%; max-width: 500px;">
        <div class="data4 data-box" onclick="location.href='<?= BASEURL ?>DetailBarang'" style="width: 50%; padding: 30px 0; text-align: center;">
            <i class="fa-solid fa-boxes-stacked"></i>
            <p><span style="font-size: 24px; font-weight: bold;"><?= $data['jumlah_detail_barang']; ?></span> Detail Barang</p>
        </div>
        <div class="data5 data-box" onclick="location.href='<?= BASEURL ?>Pengembalian'" style="width: 50%; padding: 30px 0; text-align: center;">
            <i class="fa-solid fa-rotate-left"></i>
            <p><span style="font-size: 24px; font-weight: bold;"><?= $data['jumlah_pengembalian']; ?></span> Pengembalian</p>
        </div>
    </div>

      <!-- Elemen Tengah -->
      <div class="data3 data-box" onclick="location.href='<?= BASEURL ?>merekBarang'" style="width: 90%;  padding: 30px 0; text-align: center;">
        <i class="fa-solid fa-barcode"></i>
        <p><span style="font-size: 24px; font-weight: bold;"><?= $data['jumlah_merek_barang']; ?></span> Merek Barang</p>
    </div>
</div>

            </div>

        </div>
    </div>
</div>