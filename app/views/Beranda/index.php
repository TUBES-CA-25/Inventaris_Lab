<?php
if (!isset($_SESSION['login'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}
?>


<!-- modal keluar -->
<div class="modal fade" id="konfirmasiKeluar" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-keluar">
            <div class="modal-body modal-body-keluar">
                <lottie-player 
                    src="https://lottie.host/48c004f8-57cd-4acb-a04a-de46793ba7dc/jUGVFL9qIO.json"
                    background="##FFFFFF" 
                    speed="1" 
                    class="lottie-animation" 
                    loop 
                    autoplay 
                    direction="1"
                    mode="normal">
                </lottie-player>
                <p class="modal-confirmation-text">Apakah anda yakin ingin keluar?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-modal-cancel" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger btn-modal-logout" onclick="location.href='<?= BASEURL; ?>Logout'">Keluar</button>
            </div>
        </div>
    </div>
</div>

<!-- Content Area -->
<div class="content">
    <div class="content-beranda content-beranda-overflow">
        
        <!-- Flash Message -->
        <div class="flash flash-message-container">
            <?php Flasher::flash(); ?>
        </div>
        
        <!-- Main Container -->
        <div class="beranda-main-container">
            
            <!-- Welcome Message -->
            <div class="kata text-center bg-[#0d1a4a] text-white py-6 px-6 w-[120%] h-[10%] flex items-center justify-center">
                <h2 class="text-3xl font-semibold mb-0">Selamat Datang di Sistem Inventori Barang ICLABS</h2>
            </div>
            
            <!-- Content Box -->
            <div class="beranda-content-box">
                
                <!-- Figure/Robot Image -->
                <div class="content-figure">
                    <img id="img-figure-daftar" src="<?= BASEURL ?>img/happy robot assistant.svg" alt="figure" />
                    <div class="hello-text">Hello! 👋</div>
                </div>
                
                <!-- Data Statistics Grid -->
                <div class="beranda-stats-grid">
                    
                    <!-- Row 1: Jenis Barang & Peminjaman -->
                    <div class="beranda-stats-row">
                        <div class="data-box beranda-stat-card" onclick="location.href='<?= BASEURL ?>JenisBarang'">
                            <i class="fa-solid fa-box"></i>
                            <p class="stat-content">
                                <span class="stat-number"><?= $data['jumlah_jenis_barang']; ?></span> 
                                <span class="stat-label">Jenis Barang</span>
                            </p>
                        </div>
                        
                        <div class="data-box beranda-stat-card" onclick="location.href='<?= BASEURL ?>peminjaman'">
                            <i class="fa-solid fa-receipt"></i>
                            <p class="stat-content">
                                <span class="stat-number"><?= $data['jumlah_peminjaman']; ?></span> 
                                <span class="stat-label">Peminjaman</span>
                            </p>
                        </div>
                    </div>

                    <!-- Row 2: Detail Barang & Pengembalian -->
                    <div class="beranda-stats-row">
                        <div class="data-box beranda-stat-card" onclick="location.href='<?= BASEURL ?>DetailBarang'">
                            <i class="fa-solid fa-boxes-stacked"></i>
                            <p class="stat-content">
                                <span class="stat-number"><?= $data['jumlah_detail_barang']; ?></span> 
                                <span class="stat-label">Detail Barang</span>
                            </p>
                        </div>
                        
                        <div class="data-box beranda-stat-card" onclick="location.href='<?= BASEURL ?>Pengembalian'">
                            <i class="fa-solid fa-rotate-left"></i>
                            <p class="stat-content">
                                <span class="stat-number"><?= $data['jumlah_pengembalian']; ?></span> 
                                <span class="stat-label">Pengembalian</span>
                            </p>
                        </div>
                    </div>

                    <!-- Row 3: Merek Barang (Full Width) -->
                    <div class="data-box beranda-stat-card beranda-stat-card-full" onclick="location.href='<?= BASEURL ?>merekBarang'">
                        <i class="fa-solid fa-barcode"></i>
                        <p class="stat-content">
                            <span class="stat-number"><?= $data['jumlah_merek_barang']; ?></span> 
                            <span class="stat-label">Merek Barang</span>
                        </p>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
