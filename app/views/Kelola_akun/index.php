<?php
        if (!isset($_SESSION['login']) || !in_array($_SESSION['id_role'], ['1', '2', '3', '4'])) {
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
                    <button type="button" class="btn btn-light" style="width: 100px;"
                        data-dismiss="modal">Batal</button>
                    <button type="button" style="width: 100px;" class="btn btn-danger"
                        onclick="location.href='<?=BASEURL;?>Logout'">Keluar</button>
                </div>
            </div>
        </div>
    </div>


    <div class="content">
        <div class="content-beranda" style="overflow: hidden;">
            <h3 id="title">Kelola Akun</h3>
            <div class="flash" style="width: 40%; margin-left:15px;">
                <?php Flasher::flash();?>
            </div>
            <div class="btn-fitur" style="display: flex; justify-content:space-between;">
                <button onclick="location.href='<?=BASEURL;?>Register'"
                    class="btn d-flex align-items-center justify-content-center"
                    style=" box-shadow: 5px 5px 10px 0px rgba(0, 0, 0, 0.5);">
                    <i class="fa-solid fa-plus" style="color: #ffffff"></i> Tambah
                </button>
                
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
                <div style="display: flex; align-items: center; justify-content: flex-end; box-shadow: 5px 5px 10px 0px rgba(0, 0, 0, 0.5); border-radius: 8px; overflow: hidden; width: 320px;">
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
            <table id="myTable" class="table table-hover table-sm">
                <thead class="table-info">
                    <tr>
                        <th scope="col" class="text-nowrap p-2">No.</th>
                        <th scope="col" class="text-nowrap p-2 ">Foto</th>
                        <th scope="col" class="text-nowrap p-2">Nama User</th>
                        <th scope="col" class="text-nowrap p-2">Email</th>
                        <th scope="col" class="text-nowrap p-2">No Hp</th>
                        <th scope="col" class="text-nowrap p-2">Jenis Kelamin</th>
                        <th scope="col" class="text-nowrap p-2">Alamat</th>
                        <th scope="col" class="text-nowrap p-2">Role</th>
                        <th scope="col" class="text-nowrap p-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($data['dataTampilUser'] as $row): ?>
                    <tr style="font-size: 14px;">
                        <td scope="row" class="p-2"><?= $i++; ?></td>
                        <td class="p-2"><?php
                        if($row['foto'] == "../public/img/foto-profile/"){
                            echo   '<img src="'.BASEURL . $row['foto'] .'/user.svg'.'" alt="profile"
                                style="height: 100px; width:100px; object-fit:cover;">';
                        } else{
                            echo   '<img src="'. BASEURL . $row['foto'] .'" alt="profile"
                                style="height: 100px; width:100px; object-fit:cover;">';
                        }
                        ?></td>
                        <td class="p-2"><?= $row['nama_user']; ?></td>
                        <td class="p-2"><?= $row['email']; ?></td>
                        <td class="p-2"><?= $row['no_hp_user']; ?></td>
                        <td class="p-2"><?= $row['jenis_kelamin']; ?></td>
                        <td class="p-2"><?= $row['alamat']; ?></td>
                        <td class="p-2"><?= $row['role'];?></td>
                        <td class="p-2" style="display: flex;">
                            <!-- hapus -->
                            <a class="btn d-flex align-items-center justify-content-center" data-toggle="modal"
                                data-target="#konfirmasiHapus<?=$row['id_user']?>">
                                <i class="fa-solid fa-trash-can fa-lg" style="color: #cc3030;"></i>
                            </a>

                            <!-- ubah -->
                            <a href="<?=BASEURL;?>KelolaAkun/ubahRole/<?= $row['id_user']; ?>"
                                class="btn d-flex align-items-center justify-content-center btnUbahRole"
                                data-toggle="modal" data-user="<?=$row['id_user'];?>" data-target="#modalTambah">
                                <i class="fa-solid fa-pen-to-square fa-lg" style="color: #30cc30;"></i>
                            </a>
                            <div class="modal fade" id="konfirmasiHapus<?=$row['id_user']?>" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                                                Apakah anda
                                                yakin ingin menghapus item ini?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" style="width: 100px;"
                                                data-dismiss="modal">Batal</button>
                                            <button type="button" style="width: 100px;" class="btn btn-danger"
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
            <div class="modal fade" id="modalTambah" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content" style="height: max-content; border-radius:15px">
                        <div class="modal-header">
                            <h5 class="modal-title">Ubah Role User</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body body-kelola-akun">
                            <form action="<?=BASEURL?>KelolaAkun/ubahRole" method="post">
                                <input type="hidden" name="id_user" id="id_user" value="<?=$row['id_user']?>">
                                <br>
                                <div class="ubah-role">
                                <div class="KEPLAB d-flex align-items-center">
                                    <input type="radio" name="id_role" id="keplabRole" value="1" required />
                                    <label for="keplabRole" class="mt-2 ml-2">KEPALA LAB</label>
                                </div>
                                <div class="LABORAN d-flex align-items-center">
                                    <input type="radio" name="id_role" id="laboranRole" value="2" required />
                                    <label for="laboranRole" class="mt-2 ml-2">LABORAN</label>
                                </div>
                                <div class="KORLAB d-flex align-items-center">
                                    <input type="radio" name="id_role" id="korlabRole" value="3" required />
                                    <label for="korlabRole" class="mt-2 ml-2">KOORDINATOR LAB</label>
                                </div>
                                <div class="ASISTEN d-flex align-items-center">
                                    <input type="radio" name="id_role" id="asistenRole" value="4" required />
                                    <label for="asistenRole" class="mt-2 ml-2">ASISTEN</label>
                                </div>
                                <div class="CA d-flex align-items-center">
                                    <input type="radio" name="id_role" id="caRole" value="5" required />
                                    <label for="caRole" class="mt-2 ml-2">CALON ASISTEN</label>
                                </div>
                                <div class="CCA d-flex align-items-center">
                                    <input type="radio" name="id_role" id="ccaRole" value="6" required />
                                    <label for="ccaRole" class="mt-2 ml-2">CALON CALON ASISTEN</label>
                                </div>
                                <div class="MHS d-flex align-items-center">
                                    <input type="radio" name="id_role" id="mhsRole" value="7" required />
                                    <label for="mhsRole" class="mt-2 ml-2">MAHASISWA</label>
                                </div>
                            </div>

                                <br>
                                <br>
                                <div class="modal-footer" style="margin-right: 30%;">
                                </div>
                                <br>
                                <div style="display: flex; width:100%; justify-content: end; align-items: end;">
                                    <button type="submit" id="kirim" onclick="return confirm('yakin');">Kirim</button>
                                </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>



        </div>