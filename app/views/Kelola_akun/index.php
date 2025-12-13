<?php
if (!isset($_SESSION['login']) || !in_array($_SESSION['id_role'], ['1', '2', '3', '4'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}
?>

<!-- ================= MODAL KELUAR ================= -->
<div class="modal fade" id="konfirmasiKeluar" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content modal-card">
      <div class="modal-body modal-body-center">

        <lottie-player
          src="https://lottie.host/48c004f8-57cd-4acb-a04a-de46793ba7dc/jUGVFL9qIO.json"
          class="w-[250px] h-[250px]"
          loop autoplay>
        </lottie-player>

        <p class="modal-text">
          Apakah anda yakin ingin keluar?
        </p>

      </div>
      <div class="modal-footer">
        <button class="btn-light-custom" data-dismiss="modal">Batal</button>
        <button class="btn-danger-custom"
          onclick="location.href='<?= BASEURL; ?>Logout'">
          Keluar
        </button>
      </div>
    </div>
  </div>
</div>

<!-- ================= CONTENT ================= -->
<div class="content">
  <div class="content-wrapper">

    <h3 class="page-title">Kelola Akun</h3>

    <div class="flash-wrapper">
      <?php Flasher::flash(); ?>
    </div>

    <div class="feature-bar">
      <button
        onclick="location.href='<?= BASEURL; ?>Register'"
        class="btn-primary-custom">
        <i class="fa-solid fa-plus text-white"></i>
        Tambah
      </button>
    </div>

    <!-- ================= TABLE WRAPPER ================= -->
    <div class="table-wrapper">

      <!-- HEADER TABLE -->
      <div class="table-header">

        <!-- Show Entries -->
        <div class="flex items-center text-sm gap-2">
          <label>
            Show
            <select class="form-control form-control-sm inline-block mx-2 w-auto">
              <option>10</option>
              <option>25</option>
              <option>50</option>
              <option>100</option>
            </select>
            entries
          </label>
        </div>

        <!-- Search -->
        <div class="search-box">
          <button class="search-button">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
              fill="white" width="20" height="20">
              <path
                d="M10 2a8 8 0 016.32 12.9l5.38 5.38a1 1 0 01-1.42 1.42l-5.38-5.38A8 8 0 1110 2z"/>
            </svg>
          </button>
          <input
            type="text"
            id="customSearch"
            class="search-input"
            placeholder="Cari">
        </div>

      </div>

      <!-- ================= TABLE ================= -->
      <table id="myTable" class="table table-hover table-sm table-text">
        <thead class="table-info">
          <tr>
            <th class="table-cell">No</th>
            <th class="table-cell">Foto</th>
            <th class="table-cell">Nama User</th>
            <th class="table-cell">Email</th>
            <th class="table-cell">No HP</th>
            <th class="table-cell">Jenis Kelamin</th>
            <th class="table-cell">Alamat</th>
            <th class="table-cell">Role</th>
            <th class="table-cell">Aksi</th>
          </tr>
        </thead>

        <tbody>
          <?php $i = 1; foreach ($data['dataTampilUser'] as $row): ?>
          <tr>

            <td class="table-cell"><?= $i++; ?></td>

            <td class="table-cell">
              <?php if ($row['foto'] == "../public/img/foto-profile/"): ?>
                <img src="<?= BASEURL . $row['foto']; ?>/user.svg" class="profile-img">
              <?php else: ?>
                <img src="<?= BASEURL . $row['foto']; ?>" class="profile-img">
              <?php endif; ?>
            </td>

            <td class="table-cell"><?= $row['nama_user']; ?></td>
            <td class="table-cell"><?= $row['email']; ?></td>
            <td class="table-cell"><?= $row['no_hp_user']; ?></td>
            <td class="table-cell"><?= $row['jenis_kelamin']; ?></td>
            <td class="table-cell"><?= $row['alamat']; ?></td>
            <td class="table-cell"><?= $row['role']; ?></td>

            <td class="table-cell table-action">

              <!-- HAPUS -->
              <a data-toggle="modal"
                 data-target="#konfirmasiHapus<?= $row['id_user']; ?>">
                <i class="fa-solid fa-trash-can text-red-600 text-lg"></i>
              </a>

              <!-- UBAH -->
              <a href="<?= BASEURL; ?>KelolaAkun/ubahRole/<?= $row['id_user']; ?>"
                 data-toggle="modal"
                 data-target="#modalTambah">
                <i class="fa-solid fa-pen-to-square text-green-600 text-lg"></i>
              </a>

            </td>
          </tr>

          <!-- MODAL HAPUS -->
          <div class="modal fade"
               id="konfirmasiHapus<?= $row['id_user']; ?>"
               tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content modal-card">
                <div class="modal-body modal-body-center">

                  <lottie-player
                    src="https://lottie.host/482b772b-9f0c-4065-b54d-dcc81da3b212/Dmb3I1o98u.json"
                    class="w-[250px] h-[250px]"
                    loop autoplay>
                  </lottie-player>

                  <p class="modal-text">
                    Apakah anda yakin ingin menghapus item ini?
                  </p>

                </div>
                <div class="modal-footer">
                  <button class="btn-light-custom" data-dismiss="modal">Batal</button>
                  <button class="btn-danger-custom"
                    onclick="location.href='<?= BASEURL; ?>KelolaAkun/hapusUser/<?= $row['id_user']; ?>'">
                    Hapus
                  </button>
                </div>
              </div>
            </div>
          </div>

          <?php endforeach; ?>
        </tbody>
      </table>

    </div>
  </div>
</div>

<!-- ================= MODAL UBAH ROLE ================= -->
<div class="modal fade" id="modalTambah" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content modal-card">
      <div class="modal-header">
        <h5 class="modal-title">Ubah Role User</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">
        <form action="<?= BASEURL; ?>KelolaAkun/ubahRole" method="post">
          <input type="hidden" name="id_user" id="id_user">

          <div class="space-y-3">

            <div class="role-item">
              <input type="radio" name="id_role" value="1" required>
              <label>KEPALA LAB</label>
            </div>

            <div class="role-item">
              <input type="radio" name="id_role" value="2">
              <label>LABORAN</label>
            </div>

            <div class="role-item">
              <input type="radio" name="id_role" value="3">
              <label>KOORDINATOR LAB</label>
            </div>

            <div class="role-item">
              <input type="radio" name="id_role" value="4">
              <label>ASISTEN</label>
            </div>

            <div class="role-item">
              <input type="radio" name="id_role" value="5">
              <label>CALON ASISTEN</label>
            </div>

            <div class="role-item">
              <input type="radio" name="id_role" value="6">
              <label>CALON CALON ASISTEN</label>
            </div>

            <div class="role-item">
              <input type="radio" name="id_role" value="7">
              <label>MAHASISWA</label>
            </div>

          </div>

          <div class="submit-wrapper mt-6">
            <button type="submit"
              class="submit-btn"
              onclick="return confirm('yakin');">
              Kirim
            </button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
