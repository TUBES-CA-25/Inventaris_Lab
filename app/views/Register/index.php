<form action="<?= BASEURL; ?>/Register/tambah" method="post" enctype="multipart/form-data">
  <div class="body-register row">
    <div class="figure-daftar col-12 col-lg-5">
      <div class="logo">
        <img src="<?= BASEURL ?>img/logo bg putih.svg" alt="logo" />
      </div>
      <div class="content-figure">
        <img
          id="img-figure-daftar"
          src="<?= BASEURL ?>img/happy robot assistant.svg"
          alt="figure" />
      </div>
    </div>
    <div class="form-daftar-kiri col-12 col-lg-3">
      <div class="flash" style="width: 80%;">
        <?php Flasher::flash(); ?>
      </div>
      <div class="header">
        <h2>Daftar</h2>
      </div>
      <div class="form row">
        <div class="nama col-12">
          <label for="nama_user">Nama Lengkap</label>
          <input
            type="text"
            id="nama_user"
            placeholder="Masukkan nama lengkap anda"
            maxlength="100"
            name="nama_user"
            required />
        </div>
        <div class="nim_nip col-12">
          <label for="nim_nip">NIM / NIP</label>
          <input
            type="text"
            id="nim_nip"
            name="nim_nip"
            placeholder="Masukkan NIM atau NIP anda"
            maxlength="30"
            required
            oninput="validasiInput(this)" />
        </div>
        <div class="email col-12">
          <label for="email">Email</label>
          <input
            type="email"
            name="email"
            placeholder="Masukkan email anda"
            required />
        </div>
        <div class="kata-sandi col-12">
          <label for="password">Kata sandi</label>
          <input
            type="password"
            name="password"
            id="password"
            minlength="8"
            placeholder="Masukkan kata sandi anda"
            required />
        </div>
        <div class="konfirmasi-kata-sandi col-12">
          <label for="konfirmasi-password">Konfirmasi kata sandi</label>
          <input
            type="password"
            name="konfirmasi-password"
            id="konfirmasi-password"
            minlength="8"
            placeholder="Masukkan kata sandi anda"
            required />
          <div id="error_message" style="color: red;"></div>
        </div>
      </div>
    </div>
    <div class="form-daftar-kanan col-12 col-lg-4">
      <div class="foto">
        <input
          type="file"
          name="foto"
          id="foto"
          accept="image/*"
          onchange="limitSize()"
          placeholder="Pilih foto" />
        <label for="foto">Upload Foto (Maks 2 MB) </label>
      </div>
      <div class="jenis-kelamin">
        <div class="laki-laki">
          <input
            type="radio"
            name="jenis_kelamin"
            id="laki-laki"
            value="laki-laki"
            required />
          <label for="jenis_kelamin">laki-laki</label>
        </div>
        <div class="perempuan">
          <input
            type="radio"
            name="jenis_kelamin"
            id="perempuan"
            value="perempuan"
            required />
          <label for="jenis_kelamin">perempuan</label>
        </div>
      </div>
      <div class="no_hp">
        <label for="no_hp_user">No. Hp</label>
        <input
          type="text"
          name="no_hp_user"
          id="no_hp_user"
          placeholder="Masukkan no. Hp anda"
          required
          maxlength="13"
          oninput="validasiInput(this)" />
      </div>
      <div class="alamat">
        <label for="alamat">Alamat</label>
        <input
          type="text"
          name="alamat"
          placeholder="Masukkan alamat anda"
          required
          maxlength="100" />
      </div>
      <div class="action-buttons">
        <a href="<?= BASEURL; ?>Login" class="btn-kembali">Kembali</a>

        <button type="submit" id="btn-daftar">Daftar</button>
      </div>
    </div>
  </div>
</form>