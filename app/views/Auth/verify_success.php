<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-body text-center p-5">
                    <!-- Success Icon -->
                    <div class="mb-4">
                        <div class="rounded-circle bg-success d-inline-flex align-items-center justify-content-center"
                            style="width: 80px; height: 80px;">
                            <i class="bi bi-check-circle text-white" style="font-size: 3rem;"></i>
                        </div>
                    </div>

                    <!-- Success Message -->
                    <h2 class="text-success mb-3">✓ Email Berhasil Diverifikasi!</h2>
                    <p class="text-muted mb-4">
                        Selamat! Akun Anda telah diverifikasi.<br>
                        Anda sekarang dapat login ke sistem Inventaris Lab.
                    </p>

                    <!-- Login Button -->
                    <a href="<?= BASEURL; ?>Login" class="btn btn-primary btn-lg px-5">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Login Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 15px;
        animation: fadeInUp 0.5s ease-in-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        transition: transform 0.2s;
    }

    .btn-primary:hover {
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }
</style>