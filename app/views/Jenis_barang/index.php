<?php
if (!isset($_SESSION['login']) || !in_array($_SESSION['id_role'], ['1', '2', '3', '4'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}
?>

<!-- modal keluar -->
<div class="modal fade" id="konfirmasiKeluar" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-rounded">
            <div class="modal-body modal-body-center">
                <lottie-player 
                    src="https://lottie.host/48c004f8-57cd-4acb-a04a-de46793ba7dc/jUGVFL9qIO.json"
                    background="##FFFFFF" 
                    speed="1" 
                    class="lottie-player-size" 
                    loop 
                    autoplay 
                    direction="1"
                    mode="normal">
                </lottie-player>
                <p class="modal-text-confirm">Apakah anda yakin ingin keluar?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-modal-width" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger btn-modal-width" onclick="location.href='<?= BASEURL; ?>Logout'">Keluar</button>
            </div>
        </div>
    </div>
</div>

<!-- Content Area -->
<div class="content">
    <div class="content-beranda content-overflow-hidden">
        
        <!-- Page Title -->
        <h3 id="title">Jenis Barang</h3>
        
        <!-- Flash Message -->
        <div class="flash flash-width">
            <?php Flasher::flash(); ?>
        </div>
        
        <!-- Action Buttons -->
        <div class="jenis-barang-actions">
            <button data-toggle="modal" class="btn-tambah btn-shadow" data-target="#modalTambah">
                <i class="fa-solid fa-plus text-white"></i> Tambah
            </button>
        </div>
        
        <!-- Table Container -->
        <div class="table-container">
            
            <!-- Table Header Controls -->
            <div class="table-header-controls">
                
                <!-- Entries Length Selector -->
                <div class="dataTables_length">
                    <label class="entries-label">
                        Show
                        <select name="entries_length" aria-controls="example" class="form-control form-control-sm entries-select">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        entries
                    </label>
                </div>

                <!-- Search Box -->
                <div class="search-container">
                    <button class="search-button">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" width="20" height="20">
                            <path d="M10 2a8 8 0 016.32 12.9l5.38 5.38a1 1 0 01-1.42 1.42l-5.38-5.38A8 8 0 1110 2zm0 2a6 6 0 100 12 6 6 0 000-12z"></path>
                        </svg>
                    </button>
                    <input type="text" id="customSearch" class="form-control search-input" placeholder="Cari">
                </div>
            </div>
            
            <!-- Data Table -->
            <table id="myTable" class="table table-hover table-sm">
                <thead class="table-info">
                    <tr>
                        <th scope="col" class="p-2">No.</th>
                        <th scope="col" class="p-2">Sub barang</th>
                        <th scope="col" class="p-2">Grup sub</th>
                        <th scope="col" class="p-2">Kode sub</th>
                        <th scope="col" class="p-2">Kode jenis barang</th>
                        <th scope="col" class="p-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($data['dataTampilJenisBarang'] as $row): ?>
                        <tr class="table-row">
                            <td scope="row" class="px-2"><?= $i++; ?></td>
                            <td class="p-2 text-capitalize"><?= $row['sub_barang']; ?></td>
                            <td class="p-2"><?= $row['grup_sub']; ?></td>
                            <td class="p-2"><?= $row['kode_sub']; ?></td>
                            <td class="p-2"><?= $row['kode_jenis_barang']; ?></td>
                            <td class="p-2">
                                <div class="action-buttons">
                                    <!-- Delete Button -->
                                    <a class="btn d-flex align-items-center justify-content-center" 
                                       data-toggle="modal"
                                       data-target="#konfirmasiHapus<?= $row['id_jenis_barang']; ?>">
                                        <i class="fa-solid fa-trash-can fa-lg icon-delete"></i>
                                    </a>
                                    
                                    <!-- Edit Button -->
                                    <a href="<?= BASEURL; ?>/JenisBarang/ubah/<?= $row['id_jenis_barang']; ?>"
                                       class="btn d-flex align-items-center justify-content-center tampilJenisBarangUbah"
                                       data-toggle="modal" 
                                       data-target="#modalTambah"
                                       data-id="<?= $row['id_jenis_barang']; ?>">
                                        <i class="fa-solid fa-pen-to-square fa-lg icon-edit"></i>
                                    </a>
                                </div>
                                
                                <!-- Modal Konfirmasi Hapus -->
                                <div class="modal fade" id="konfirmasiHapus<?= $row['id_jenis_barang']; ?>" 
                                     tabindex="-1" 
                                     role="dialog" 
                                     aria-labelledby="exampleModalCenterTitle" 
                                     aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content modal-rounded">
                                            <div class="modal-body modal-body-center">
                                                <lottie-player
                                                    src="https://lottie.host/482b772b-9f0c-4065-b54d-dcc81da3b212/Dmb3I1o98u.json"
                                                    background="##FFFFFF" 
                                                    speed="1" 
                                                    class="lottie-player-size" 
                                                    loop
                                                    autoplay 
                                                    direction="1" 
                                                    mode="normal">
                                                </lottie-player>
                                                <p class="modal-text-confirm">Apakah anda yakin ingin menghapus item ini?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light btn-modal-width" data-dismiss="modal">Batal</button>
                                                <button type="button" class="btn btn-danger btn-modal-width" 
                                                        onclick="location.href='<?= BASEURL; ?>JenisBarang/hapus/<?= $row['id_jenis_barang']; ?>'">Hapus</button>
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
        
        <!-- Modal Tambah/Ubah Jenis Barang -->
        <div class="modal fade" id="modalTambah" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content modal-form">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Jenis Barang</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="<?= BASEURL ?>JenisBarang/tambahJenisBarang" method="post">
                            <input type="hidden" name="id_jenis_barang" id="id_jenis_barang">
                            
                            <!-- Sub Barang -->
                            <div class="form-group-custom">
                                <label for="sub_barang" class="form-label-custom">Sub barang</label>
                                <input type="text" 
                                       name="sub_barang" 
                                       id="sub_barang" 
                                       class="form-input-custom"
                                       oninput="camelCase()" 
                                       required>
                            </div>
                            
                            <!-- Grup Sub -->
                            <div class="form-group-custom">
                                <label for="grup_sub" class="form-label-custom">Grup sub</label>
                                <select name="grup_sub" 
                                        id="grup_sub" 
                                        class="form-input-custom"
                                        required>
                                    <option>-- Pilih --</option>
                                    <option value="C">C</option>
                                    <option value="S">S</option>
                                    <option value="J">J</option>
                                    <option value="F">F</option>
                                    <option value="M">M</option>
                                    <option value="T">T</option>
                                    <option value="K">K</option>
                                    <option value="U">U</option>
                                </select>
                            </div>
                            
                            <!-- Kode Sub -->
                            <div class="form-group-custom">
                                <label for="kode_sub" class="form-label-custom">Kode sub</label>
                                <input type="text" 
                                       name="kode_sub" 
                                       id="kode_sub" 
                                       class="form-input-custom"
                                       oninput="uppercaseInput(this)" 
                                       maxlength="3"
                                       required>
                            </div>
                            
                            <!-- Submit Button -->
                            <div class="form-submit-container">
                                <button type="submit" id="kirim" class="btn-submit">Kirim</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>