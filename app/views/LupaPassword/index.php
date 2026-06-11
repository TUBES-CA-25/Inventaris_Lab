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
                <h2>Lupa Kata Sandi</h2>
                <p>Masukkan email yang terdaftar untuk menerima link reset</p>
            </div>

            <div class="flash-wrapper">
                <?php Flasher::flash();?>
            </div>

            <form action="<?= BASEURL;?>LupaPassword/sendResetLink" method="post">
                
                <div class="input-wrapper">
                    <div class="form-group">
                        <label for="email">Email Terdaftar</label>
                        <input type="email" name="email" id="email" placeholder="Contoh: user@email.com" required autocomplete="email" value="<?= htmlspecialchars($data['cooldown_email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                </div>

                <div class="button-section" style="margin-top: 2rem;">
                    <button type="submit" class="btn-login">Kirim Link Reset</button>
                    
                    <div class="register-prompt" style="margin-top: 1rem;">
                        <a href="<?=BASEURL; ?>Login" class="register-link" style="font-weight: 500;">Kembali ke Halaman Login</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    let remaining = <?= isset($data['cooldown']) ? (int)$data['cooldown'] : 0 ?>;
    const emailInput = document.getElementById('email');
    const submitBtn = document.querySelector('.btn-login');
    const cooldownEmail = "<?= isset($data['cooldown_email']) ? htmlspecialchars($data['cooldown_email'], ENT_QUOTES, 'UTF-8') : '' ?>";

    if (remaining > 0 && cooldownEmail !== '') {
        // Buat indikator countdown jika belum ada
        let countdownWrapper = document.getElementById('cooldown-indicator');
        if (!countdownWrapper) {
            countdownWrapper = document.createElement('div');
            countdownWrapper.id = 'cooldown-indicator';
            countdownWrapper.style.marginTop = '1rem';
            countdownWrapper.style.padding = '0.75rem';
            countdownWrapper.style.borderRadius = '8px';
            countdownWrapper.style.backgroundColor = 'rgba(239, 68, 68, 0.1)';
            countdownWrapper.style.border = '1px solid rgba(239, 68, 68, 0.2)';
            countdownWrapper.style.color = '#dc2626';
            countdownWrapper.style.fontSize = '0.9rem';
            countdownWrapper.style.fontWeight = '500';
            countdownWrapper.style.textAlign = 'center';
            countdownWrapper.style.transition = 'all 0.3s ease';
            submitBtn.parentNode.insertBefore(countdownWrapper, submitBtn.nextSibling);
        }

        let intervalId;

        function updateTimer() {
            if (remaining <= 0) {
                countdownWrapper.style.display = 'none';
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Kirim Link Reset';
                submitBtn.style.opacity = '1';
                submitBtn.style.cursor = 'pointer';
                clearInterval(intervalId);
                return;
            }

            // Update button text and disabled state
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.6';
            submitBtn.style.cursor = 'not-allowed';
            submitBtn.innerHTML = `Harap Tunggu (${remaining}s)`;
            
            countdownWrapper.innerHTML = `<i class="fas fa-clock" style="margin-right: 5px;"></i> Silakan tunggu <b>${remaining} detik</b> untuk mengirim ulang ke <b>${escapeHtml(cooldownEmail)}</b>.`;
            remaining--;
        }

        function escapeHtml(str) {
            return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
        }

        intervalId = setInterval(updateTimer, 1000);
        updateTimer();

        // Enable button dynamically if user types a different email
        emailInput.addEventListener('input', function() {
            if (emailInput.value.trim() !== cooldownEmail) {
                countdownWrapper.style.display = 'none';
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Kirim Link Reset';
                submitBtn.style.opacity = '1';
                submitBtn.style.cursor = 'pointer';
            } else {
                if (remaining > 0) {
                    countdownWrapper.style.display = 'block';
                    updateTimer(); // Akan mendisable button lagi
                }
            }
        });
    }
});
</script>
