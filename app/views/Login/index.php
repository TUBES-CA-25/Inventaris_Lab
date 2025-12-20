<form action="<?= BASEURL;?>Login/login" method="post" class="login-form">
  <div class="body-login">
    <div class="figure">
      <div class="logo">
        <img src="<?=BASEURL;?>img/logo bg hitam.svg" alt="logo">
      </div>
      <div class="content-figure">
        <img id="login-figure" src="<?=BASEURL ?>img/login figure.svg" alt="figure">
      </div>
    </div>

    <div class="form-login">
      <div class="flash">
        <?php Flasher::flash();?>
      </div>

      <div class="container1">
        <div class="header">
          <h2>Masuk</h2>
        </div>

        <div class="input-group">
          <label for="email">Email</label>
          <input type="email" name="email" id="email" placeholder="Masukkan email anda" required>
        </div>

        <div class="input-group">
          <label for="kata-sandi">Kata sandi</label>
          <input type="password" name="kata-sandi" id="kata-sandi" placeholder="Masukkan kata sandi anda" required>
        </div>

        <div class="forgot-password">
          <a href="<?=BASEURL;?>LupaKataSandi">Lupa kata sandi?</a>
        </div>

        <div class="button-login">
          <button type="submit">Masuk</button>
          <span class="register-link">
            <p>Belum punya akun?</p>
            <a href="<?=BASEURL; ?>Register">Buat akun</a>
          </span>
        </div>
      </div>
    </div>
  </div>
</form>
