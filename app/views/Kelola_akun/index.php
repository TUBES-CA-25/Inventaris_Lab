<?php
    if (!isset($_SESSION['login']) || !in_array($_SESSION['id_role'], ['1', '2', '3', '4'])) {
        header("Location:" . BASEURL . "Login");
        exit;
    }
?>

<div class="content">
    <div class="content-beranda">
        
        <h3 id="title">Kelola akun</h3>

        <div class="flash" style="width: 100%; margin-bottom: 20px;">
            <?php Flasher::flash();?>
        </div>

        <div class="stats-container">
            <div class="card-stat dark">
                <div>
                    <h5>Total admin</h5>
                    <h2>1</h2>
                </div>
                <div class="card-icon">
                    <i class="fa-solid fa-crown"></i>
                </div>
            </div>

            <div class="card-stat light">
                <div>
                    <h5>Total asisten</h5>
                    <h2>3</h2>
                </div>
                <div class="card-icon">
                    <i class="fa-solid fa-star"></i>
                </div>
            </div>

            <div class="card-stat light">
                <div>
                    <h5>Total user</h5>
                    <h2>10</h2>
                </div>
                <div class="card-icon">
                    <i class="fa-solid fa-user"></i>
                </div>
            </div>
        </div>

        <div class="action-bar">
            <button onclick="location.href='<?=BASEURL;?>Register'" class="btn-tambah">
                <i class="fa-solid fa-plus"></i> Tambah
            </button>

            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="customSearch" placeholder="Search...">
            </div>
        </div>

        <div class="table-responsive">
            <table id="myTable" class="custom-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th> <th>No Hp</th>
                        <th>Alamat</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($data['dataTampilUser'] as $row): ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <span><?= $row['nama_user']; ?></span>
                            </div>
                        </td>
                        <td><?= $row['email']; ?></td>
                        <td><?= $row['role']; ?></td>
                        <td><?= $row['no_hp_user']; ?></td>
                        <td><?= $row['alamat']; ?></td>
                        <td style="text-align: center;">
                            <button class="btn-action btnUbahRole" 
                                    data-toggle="modal" 
                                    data-target="#modalRole"
                                    onclick="setModalData('<?=$row['id_user']?>', '<?=$row['id_role'] //Asumsi ada id_role di row?>')"> 
                                <i class="fa-regular fa-pen-to-square" style="color: #30cc30;"></i>
                            </button>
                            
                            <button class="btn-action" data-toggle="modal" data-target="#konfirmasiHapus<?=$row['id_user']?>">
                                <i class="fa-regular fa-trash-can" style="color: #cc3030;"></i>
                            </button>

                            <div class="modal fade" id="konfirmasiHapus<?=$row['id_user']?>" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content" style="border-radius: 15px;">
                                        <div class="modal-body d-flex flex-column align-items-center">
                                            <lottie-player src="https://lottie.host/482b772b-9f0c-4065-b54d-dcc81da3b212/Dmb3I1o98u.json"
                                                background="##FFFFFF" speed="1" style="width: 250px; height: 250px" loop autoplay direction="1" mode="normal"></lottie-player>
                                            <p style="color:#385161; opacity: 0.6; font-weight: 500;">Yakin ingin menghapus data ini?</p>
                                        </div>
                                        <div class="modal-footer border-0">
                                            <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                                            <button type="button" class="btn btn-danger"
                                                onclick="location.href='<?=BASEURL;?>KelolaAkun/hapusUser/<?= $row['id_user']; ?>'">Hapus</button>
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

    </div>
</div>

<div class="modal fade" id="modalRole" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="max-width: 700px;">
        <div class="modal-content modal-content-custom">
            
            <button type="button" class="close btn-close-custom" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>

            <h2 class="modal-title-custom">Ubah role user</h2>

            <form action="<?=BASEURL?>KelolaAkun/ubahRole" method="post">
                <input type="hidden" name="id_user" id="id_user_modal">
                
                <div class="modal-body-layout">
                    <div class="role-list">
                        <?php 
                        // Daftar Role Lengkap Anda
                        $roles = [
                            1 => 'Kepala Lab', 
                            2 => 'Laboran', 
                            3 => 'Koordinator Lab', 
                            4 => 'Asisten', 
                            5 => 'Calon Asisten', 
                            6 => 'Calon Calon Asisten', 
                            7 => 'Mahasiswa'
                        ];
                        ?>
                        <?php foreach($roles as $val => $label): ?>
                        <label class="radio-item">
                            <input type="radio" name="id_role" value="<?=$val?>" required>
                            <?=$label?>
                        </label>
                        <?php endforeach; ?>
                    </div>

                    <div class="illustration-container">
                        <i class="fa-solid fa-stairs" style="font-size: 150px; color: black;"></i>
                    </div>
                </div>

                <div class="modal-footer-custom">
                    <button type="submit" class="btn-simpan">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Fungsi ini dipanggil saat tombol edit diklik
    // Pastikan Anda memanggil ini di tombol edit tabel: onclick="setModalData('ID_USER_DISINI')"
    function setModalData(id) {
        document.getElementById('id_user_modal').value = id;
    }
    
    // Opsional: Jika ingin jQuery untuk bootstrap modal events
    $('.btnUbahRole').on('click', function() {
        var id = $(this).data('user');
        $('#id_user_modal').val(id);
    });
</script>