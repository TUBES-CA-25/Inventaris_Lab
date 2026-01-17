<div class="body-login">
    <div class="figure-section">
        <div class="logo-container">
            <img src="<?=BASEURL;?>img/logo bg hitam.svg" alt="logo">
        </div>
        <div class="figure-content">
            <img id="login-figure" src="<?=BASEURL ?>img/login figure.svg" alt="figure">
        </div>
    </div>

    <div class="form-section">
        <div class="form-container">
            
            <div class="form-header">
                <h2>Selamat Datang</h2>
                <p>Silakan masuk ke akun Anda</p>
            </div>

            <div class="flash-wrapper">
                <?php Flasher::flash();?>
            </div>

            <form action="<?= BASEURL;?>Login/login" method="post">
                
                <div class="input-wrapper">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" placeholder="Contoh: user@email.com" required autocomplete="email">
                    </div>

                    <div class="form-group">
                        <label for="kata-sandi">Kata Sandi</label>
                        <input type="password" name="kata-sandi" id="kata-sandi" placeholder="Masukkan kata sandi" required>
                    </div>
                    
                    <div class="forgot-password-wrapper">
                        <a href="<?=BASEURL;?>LupaKataSandi" class="forgot-password-link">Lupa kata sandi?</a>
                    </div>
                </div>

                <div class="button-section">
                    <button type="submit" class="btn-login">Masuk</button>
                    
                    <div class="register-prompt">
                        <p>Belum punya akun?</p>
                        <a href="<?=BASEURL; ?>Register" class="register-link">Buat akun</a>
                    </div>
                </div>
            </form>
            </div>
    </div>
</div>