<?php
if (!isset($_SESSION['login']) || !in_array($_SESSION['id_role'], ['1', '2', '3', '4'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}
?>

<!-- modal keluar -->
<div class="modal fade modal-custom" id="konfirmasiKeluar" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <lottie-player 
                    src="https://lottie.host/48c004f8-57cd-4acb-a04a-de46793ba7dc/jUGVFL9qIO.json"
                    background="##FFFFFF" 
                    speed="1" 
                    style="width: 250px; height: 250px; margin: 0 auto;" 
                    loop 
                    autoplay>
                </lottie-player>
                <p class="mt-3 mb-0" style="color: #64748b; font-weight: 500;">
                    Apakah anda yakin ingin keluar?
                </p>
            </div>
            <div class="modal-footer d-flex justify-content-end gap-2">
                <button type="button" class="btn-secondary-custom" data-dismiss="modal">Batal</button>
                <button type="button" class="btn-danger-custom" onclick="location.href='<?= BASEURL; ?>Logout'">
                    Keluar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Content -->
<div class="content">
    <div class="content-beranda" style="overflow: hidden;">
        <!-- Header -->
        <h3 id="title" class="mb-4" style="font-weight: 700; color: #1e293b;">Merek Barang</h3>
        
        <!-- Flash Message -->
        <div class="flash mb-4" style="width: 40%; margin-left: 15px;">
            <?php Flasher::flash(); ?>
        </div>
        
        <!-- Button Section -->
        <div class="btn-fitur mb-4">
            <button data-toggle="modal" 
                    class="btn-primary-custom btn-tambah-merek" 
                    data-target="#modalTambah">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah</span>
            </button>
        </div>
        
        <!-- Table Container -->
        <div class="table-container">
            <!-- Table Header Controls -->
            <div class="table-header-sticky">
                <!-- Entries Dropdown -->
                <div class="entries-container">
                    <span>Show</span>
                    <select name="entries_length" class="entries-select form-control-sm">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span>entries</span>
                </div>

                <!-- Search Box -->
                <div class="search-box-container">
                    <button class="search-button">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" width="20" height="20">
                            <path d="M10 2a8 8 0 016.32 12.9l5.38 5.38a1 1 0 01-1.42 1.42l-5.38-5.38A8 8 0 1110 2zm0 2a6 6 0 100 12 6 6 0 000-12z"></path>
                        </svg>
                    </button>
                    <input type="text" 
                           id="customSearch" 
                           class="search-input form-control" 
                           placeholder="Cari merek barang...">
                </div>
            </div>

            <!-- Table -->
            <table id="myTable" class="table table-hover table-sm table-custom">
                <thead>
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Merek Barang</th>
                        <th scope="col">Kode Merek</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($data['dataTampilMerekBarang'] as $row): ?>
                        <tr>
                            <td class="font-weight-medium"><?= $i++; ?></td>
                            <td class="text-capitalize"><?= $row['nama_merek_barang']; ?></td>
                            <td class="font-weight-medium"><?= $row['kode_merek_barang']; ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <!-- Delete Button -->
                                    <a class="action-btn-custom action-btn-delete" 
                                       data-toggle="modal"
                                       data-target="#konfirmasiHapus<?= $row['id_merek_barang'] ?>">
                                        <i class="fa-solid fa-trash-can fa-lg" style="color: #dc2626;"></i>
                                    </a>
                                    
                                    <!-- Edit Button -->
                                    <a href="<?= BASEURL; ?>MerekBarang/ubah/<?= $row['id_merek_barang']; ?>"
                                       class="action-btn-custom action-btn-edit tampilMerekBarangUbah"
                                       data-toggle="modal" 
                                       data-target="#modalTambah" 
                                       data-id="<?= $row['id_merek_barang']; ?>">
                                        <i class="fa-solid fa-pen-to-square fa-lg" style="color: #059669;"></i>
                                    </a>
                                </div>

                                <!-- Modal Konfirmasi Hapus -->
                                <div class="modal fade modal-custom" id="konfirmasiHapus<?= $row['id_merek_barang'] ?>" tabindex="-1" role="dialog">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body text-center py-4">
                                                <lottie-player
                                                    src="https://lottie.host/482b772b-9f0c-4065-b54d-dcc81da3b212/Dmb3I1o98u.json"
                                                    background="##FFFFFF" 
                                                    speed="1" 
                                                    style="width: 250px; height: 250px; margin: 0 auto;" 
                                                    loop 
                                                    autoplay>
                                                </lottie-player>
                                                <p class="mt-3 mb-0" style="color: #64748b; font-weight: 500;">
                                                    Apakah anda yakin ingin menghapus item ini?
                                                </p>
                                            </div>
                                            <div class="modal-footer d-flex justify-content-end gap-2">
                                                <button type="button" class="btn-secondary-custom" data-dismiss="modal">
                                                    Batal
                                                </button>
                                                <button type="button" class="btn-danger-custom"
                                                        onclick="location.href='<?= BASEURL; ?>MerekBarang/hapus/<?= $row['id_merek_barang']; ?>'">
                                                    Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Modal Tambah/Edit -->
        <div class="modal fade modal-custom" id="modalTambah" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title title-merek">Tambah Merek Barang</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" style="font-size: 1.5rem;">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body body-merek">
                        <form action="<?= BASEURL ?>MerekBarang/tambahMerekBarang" method="post">
                            <input type="hidden" name="id_merek_barang" id="id_merek_barang">
                            
                            <!-- Merek Barang -->
                            <div class="form-group-custom mb-4">
                                <label for="nama_merek_barang">Merek Barang</label>
                                <input type="text" 
                                       name="nama_merek_barang" 
                                       id="nama_merek_barang"
                                       class="form-control-custom text-capitalize" 
                                       required
                                       placeholder="Masukkan nama merek barang">
                            </div>
                            
                            <!-- Kode Merek -->
                            <div class="form-group-custom mb-4">
                                <label for="kode_merek_barang">Kode Merek</label>
                                <input type="text" 
                                       name="kode_merek_barang" 
                                       id="kode_merek_barang"
                                       class="form-control-custom" 
                                       minlength="3"
                                       maxlength="3" 
                                       required 
                                       oninput="validasiInput(this)" 
                                       placeholder="cth: 00x">
                                <small class="text-muted">Masukkan 3 karakter untuk kode merek</small>
                            </div>
                            
                            <!-- Submit Button -->
                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" id="kirim" class="btn-primary-custom">
                                    <i class="fa-solid fa-paper-plane"></i>
                                    <span>Simpan</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>