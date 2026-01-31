<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-body text-center p-5">
                    <!-- Error Icon -->
                    <div class="mb-4">
                        <div class="rounded-circle bg-warning d-inline-flex align-items-center justify-content-center"
                            style="width: 80px; height: 80px;">
                            <i class="bi bi-exclamation-triangle text-white" style="font-size: 3rem;"></i>
                        </div>
                    </div>

                    <!-- Error Message -->
                    <h2 class="text-warning mb-3">⚠ Verifikasi Gagal</h2>
                    <p class="text-muted mb-4">
                        <?= isset($data['message']) ? htmlspecialchars($data['message']) : 'Terjadi kesalahan pada verifikasi email.'; ?>
                    </p>

                    <div class="alert alert-warning text-start">
                        <strong>Kemungkinan penyebab:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Link verifikasi sudah expired (lebih dari
                                <?= VERIFICATION_LINK_EXPIRY; ?> jam)
                            </li>
                            <li>Token verifikasi tidak valid</li>
                            <li>Email sudah diverifikasi sebelumnya</li>
                        </ul>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4">
                        <a href="<?= BASEURL; ?>Login" class="btn btn-primary">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Ke Halaman Login
                        </a>
                        <a href="<?= BASEURL; ?>Register" class="btn btn-outline-secondary">
                            <i class="bi bi-person-plus me-2"></i>Daftar Ulang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="<?= BASEURL; ?>css/verify_error.css">