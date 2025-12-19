<form action="<?= BASEURL; ?>Login/login" method="post">
  <div class="body-login">

    <!-- ================= LEFT SECTION - FIGURE ================= -->
    <div class="figure-section">

      <!-- Logo -->
      <div class="logo-container">
        <img src="<?= BASEURL; ?>img/logo bg hitam.svg" alt="Logo Aplikasi">
      </div>

      <!-- Figure Image -->
      <div class="figure-content">
        <img
          id="login-figure"
          src="<?= BASEURL; ?>img/login figure.svg"
          alt="Login Illustration">
      </div>

    </div>

    <!-- ================= RIGHT SECTION - FORM ================= -->
    <div class="form-section">
      <div class="form-container">

        <!-- Flash Message -->
        <div class="flash-wrapper">
          <?php Flasher::flash(); ?>
        </div>

        <!-- Form Header -->
        <div class="form-header">
          <h2>Masuk</h2>
          <p>Silakan masuk ke akun Anda</p>
        </div>

        <!-- Input Fields -->
        <div class="input-wrapper">

          <!-- Email Input -->
          <div class="form-group">
            <label for="email">Email</label>
            <input
              type="email"
              name="email"
              id="email"
              placeholder="Masukkan email anda"
              required
              autocomplete="email">
          </div>

          <!-- Password Input -->
          <div class="form-group">
            <label for="kata-sandi">Kata Sandi</label>
            <input
              type="password"
              name="kata-sandi"
              id="kata-sandi"
              placeholder="Masukkan kata sandi anda"
              required
              autocomplete="current-password">
          </div>

          <!-- Forgot Password Link -->
          <div class="forgot-password-wrapper">
            <a href="<?= BASEURL; ?>LupaKataSandi" class="forgot-password-link">
              Lupa kata sandi?
            </a>
          </div>

        </div>

        <!-- Button Section -->
        <div class="button-section">

          <!-- Login Button -->
          <button type="submit" class="btn-login">
            Masuk
          </button>

          <!-- Register Prompt -->
          <div class="register-prompt">
            <p>Belum punya akun?</p>
            <a href="<?= BASEURL; ?>Register" class="register-link">
              Buat akun
            </a>
          </div>

        </div>

      </div>
    </div>

  </div>