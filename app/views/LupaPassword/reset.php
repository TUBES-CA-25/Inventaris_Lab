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
                <h2>Kata Sandi Baru</h2>
                <p>Mereset kata sandi untuk <strong><?= htmlspecialchars($data['email']); ?></strong></p>
            </div>

            <div class="flash-wrapper">
                <?php Flasher::flash();?>
            </div>

            <form action="<?= BASEURL;?>LupaPassword/processReset" method="post">
                <input type="hidden" name="token" value="<?= htmlspecialchars($data['token']); ?>">
                <div class="input-wrapper">
                    <div class="form-group">
                        <label for="password">Kata Sandi Baru</label>
                        <input type="password" name="password" id="password" placeholder="Masukkan kata sandi baru" required minlength="6">
                    </div>

                    <div class="form-group">
                        <label for="konfirmasi-password">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" name="konfirmasi-password" id="konfirmasi-password" placeholder="Ketik ulang kata sandi baru" required>
                    </div>
                </div>

                <div class="button-section" style="margin-top: 2rem;">
                    <button type="submit" class="btn-login">Simpan Kata Sandi</button>
                    
                    <div class="register-prompt" style="margin-top: 1rem;">
                        <a href="<?=BASEURL; ?>Login" class="register-link" style="font-weight: 500;">Batal dan Kembali ke Login</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
