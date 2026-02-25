<?php


$isEdit = isset($_SESSION['edit_mode']) && $_SESSION['edit_mode'] === true;
$headerData = $isEdit ? $_SESSION['edit_header'] : [];
$detailMap = $isEdit ? $_SESSION['edit_details_map'] : [];

$formAction = $isEdit ? BASEURL . 'Peminjaman/prosesUpdatePeminjaman' : BASEURL . 'Peminjaman/prosesTambahPeminjaman';

// --- DATA RESTORATION (PRIORITIZE CONTROLLER DATA) ---
$val_judul = !empty($data['val_judul']) ? $data['val_judul'] : ($isEdit ? $headerData['judul_kegiatan'] : '');
$val_tgl_aju = !empty($data['val_tgl_aju']) ? $data['val_tgl_aju'] : ($isEdit ? $headerData['tanggal_pengajuan'] : date('Y-m-d'));
$val_tgl_mulai = !empty($data['val_tgl_mulai']) ? $data['val_tgl_mulai'] : ($isEdit ? $headerData['tanggal_peminjaman'] : '');
$val_tgl_akhir = !empty($data['val_tgl_akhir']) ? $data['val_tgl_akhir'] : ($isEdit ? $headerData['tanggal_pengembalian'] : '');
$val_ket = !empty($data['val_ket']) ? $data['val_ket'] : ($isEdit && isset($headerData['keterangan_peminjaman']) ? $headerData['keterangan_peminjaman'] : '');
$val_tujuan_lain = $data['val_tujuan_lain'] ?? '';
$val_tujuan_ta = $data['val_tujuan_ta'] ?? '';
$val_tujuan_riset = $data['val_tujuan_riset'] ?? '';
$val_dosen = $data['val_dosen'] ?? '';
// ------------------------------------------------------
?>

<div class="content">
    <div class="container-fluid">
        <div class="form-card">
            <form id="formPeminjaman" action="<?= $formAction; ?>" method="post">
                <div class="row">
                    <div class="col-md-7">
                        <h2 class="form-title"><?= $isEdit ? 'Edit Peminjaman' : 'Peminjaman'; ?></h2>
                        <div class="flash">
                            <?php Flasher::flash(); ?>
                        </div>

                        <div class="gap-row">
                            <?php if ($_SESSION['id_role'] == '6'): ?>
                                <label class="lbl">Tujuan Peminjaman</label>
                                <div class="icon-wrap">
                                    <select name="judul_kegiatan" id="judul_kegiatan" class="inp-custom" required
                                        onchange="toggleTujuanDetail(this.value)">
                                        <option value="">-- Pilih Tujuan --</option>
                                        <option value="Tugas Akhir" <?= ($val_judul == 'Tugas Akhir' || strpos($val_judul, 'Tugas Akhir:') !== false) ? 'selected' : ''; ?>>
                                            Tugas Akhir</option>
                                        <option value="Riset" <?= ($val_judul == 'Riset' || strpos($val_judul, 'Riset:') !== false) ? 'selected' : ''; ?>>Riset</option>
                                        <option value="Lain-lain" <?= ($val_judul == 'Lain-lain' || strpos($val_judul, 'Lain-lain:') !== false) ? 'selected' : ''; ?>>
                                            Lain-lain</option>
                                    </select>
                                    <i class="fa-solid fa-caret-down icon-inside" style="color: #1e293b;"></i>
                                </div>

                                <div id="wrap_tujuan_ta"
                                    style="display: <?= ($val_judul == 'Tugas Akhir' || strpos($val_judul, 'Tugas Akhir:') !== false) ? 'block' : 'none'; ?>; margin-top: 10px;">
                                    <label class="lbl">Judul Tugas Akhir</label>
                                    <input type="text" name="tujuan_ta" id="tujuan_ta" class="inp-custom"
                                        value="<?= $val_tujuan_ta; ?>" placeholder="Masukkan judul tugas akhir...">
                                </div>

                                <div id="wrap_tujuan_riset"
                                    style="display: <?= ($val_judul == 'Riset' || strpos($val_judul, 'Riset:') !== false) ? 'block' : 'none'; ?>; margin-top: 10px;">
                                    <label class="lbl">Judul Riset</label>
                                    <input type="text" name="tujuan_riset" id="tujuan_riset" class="inp-custom"
                                        value="<?= $val_tujuan_riset; ?>" placeholder="Masukkan judul riset...">
                                </div>

                                <div id="wrap_tujuan_lain"
                                    style="display: <?= ($val_judul == 'Lain-lain' || strpos($val_judul, 'Lain-lain:') !== false) ? 'block' : 'none'; ?>; margin-top: 10px;">
                                    <label class="lbl">Detail Tujuan Lainnya</label>
                                    <input type="text" name="tujuan_lain" id="tujuan_lain" class="inp-custom"
                                        value="<?= $val_tujuan_lain; ?>" placeholder="Masukkan tujuan lainnya...">
                                </div>

                                <div id="wrap_dosen"
                                    style="display: <?= ($val_judul && $val_judul != 'Peminjaman Biasa') ? 'block' : 'none'; ?>; margin-top: 10px;">
                                    <label class="lbl">Nama Dosen Pembimbing</label>
                                    <input type="text" name="dosen_pembimbing" id="dosen_pembimbing" class="inp-custom"
                                        value="<?= $val_dosen; ?>" placeholder="Masukkan nama dosen pembimbing...">
                                </div>
                            <?php else: ?>
                                <label class="lbl">Judul kegiatan</label>
                                <input type="text" name="judul_kegiatan" class="inp-custom" value="<?= $val_judul; ?>"
                                    required>
                            <?php endif; ?>
                        </div>

                        <div class="gap-row">
                            <label class="lbl">Tanggal pengajuan</label>
                            <div class="inp-custom d-flex align-items-center"
                                style="background-color: #f1f5f9; cursor: default; color: #64748b;">
                                <?= date('d-m-Y', strtotime($val_tgl_aju)); ?>
                                <input type="hidden" name="tanggal_pengajuan" value="<?= $val_tgl_aju; ?>">
                            </div>
                        </div>

                        <style>
                            .date-tooltip-wrapper {
                                position: relative;
                            }

                            .date-info-tooltip {
                                visibility: hidden;
                                width: 220px;
                                background-color: #0c1740;
                                color: #fff;
                                text-align: center;
                                border-radius: 10px;
                                padding: 10px 15px;
                                position: absolute;
                                z-index: 1000;
                                bottom: 125%;
                                left: 50%;
                                transform: translateX(-50%);
                                opacity: 0;
                                transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                                font-size: 13px;
                                line-height: 1.4;
                                pointer-events: none;
                                box-shadow: 0 10px 20px rgba(12, 23, 64, 0.2);
                                font-weight: 500;
                            }

                            .date-info-tooltip::after {
                                content: "";
                                position: absolute;
                                top: 100%;
                                left: 50%;
                                margin-left: -6px;
                                border-width: 6px;
                                border-style: solid;
                                border-color: #0c1740 transparent transparent transparent;
                            }

                            /* Tampilkan tooltip hanya jika dalam keadaan 'locked' (belum ada tgl mulai) */
                            .date-tooltip-wrapper.is-locked:hover .date-info-tooltip {
                                visibility: visible;
                                opacity: 1;
                                bottom: 135%;
                            }
                        </style>

                        <div class="row row-item-grid gap-row">
                            <div class="col-md-6">
                                <label class="lbl">Mulai dari tanggal</label>
                                <div class="icon-wrap">
                                    <input type="date" name="tanggal_peminjaman" id="tanggal_peminjaman"
                                        class="inp-custom" value="<?= $val_tgl_mulai; ?>" min="<?= date('Y-m-d'); ?>"
                                        required onclick="this.showPicker()" onchange="updateReturnDateConstraints()">
                                    <i class="fa-regular fa-calendar icon-inside" style="color: #1e293b;"></i>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="lbl">Sampai tanggal</label>
                                <div class="date-tooltip-wrapper is-locked" id="return_date_wrapper">
                                    <div class="date-info-tooltip">
                                        <i class="fas fa-info-circle mr-1"></i> Silakan masukkan tanggal mulai peminjaman terlebih dahulu
                                    </div>
                                    <div class="icon-wrap">
                                        <input type="date" name="tanggal_pengembalian" id="tanggal_pengembalian"
                                            class="inp-custom" value="<?= $val_tgl_akhir; ?>" min="<?= date('Y-m-d'); ?>"
                                            required onclick="this.showPicker()">
                                        <i class="fa-regular fa-calendar icon-inside" style="color: #1e293b;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="gap-row">
                            <label class="lbl">Komentar / Keterangan Peminjaman</label>
                            <textarea name="keterangan_peminjaman" class="inp-custom" rows="3"
                                placeholder="Contoh: Untuk keperluan praktikum..."><?= $val_ket; ?></textarea>
                        </div>

                    </div>

                    <div class="col-md-5 right-section1">
                        <div class="content-figure">
                            <img id="img-figure-daftar" src="<?= BASEURL ?>img/happy robot assistant.svg"
                                alt="figure" />
                            <div class="hello-text">Hello! 👋</div>
                        </div>
                    </div>
                </div>

                <hr style="border-top: 2px dashed #e2e8f0; margin: 30px 0;">

                <div class="row">
                    <div class="col-12">
                        <?php if (!empty($data['barang_selected'])): ?>

                            <?php foreach ($data['barang_selected'] as $item):
                                // --- PERBAIKAN: Gunakan $item, bukan $unit ---
                                $id = $item['id_jenis_barang'];
                                $curr_jml = 1;
                                $curr_unit = '';

                                if ($isEdit && isset($detailMap[$id]) && !empty($detailMap[$id])) {
                                    $saved_data = array_shift($detailMap[$id]);
                                    $curr_jml = $saved_data['jumlah'];
                                    $curr_unit = $saved_data['keterangan'];
                                }
                                ?>
                                <div class="item-row">
                                    <div class="row row-item-grid align-items-end">

                                        <div class="col-md-5">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <label class="lbl mb-0">Jenis Barang</label>
                                                <button type="button" class="btn-cancel-item"
                                                    onclick="konfirmasiHapus('<?= BASEURL; ?>Peminjaman/hapusItem/<?= $item['hapus_id']; ?>')">
                                                    <i class="fa-solid fa-circle-xmark"></i> Hapus
                                                </button>
                                            </div>
                                            <div class="icon-wrap">
                                                <input type="hidden" name="id_jenis_barang[]"
                                                    value="<?= $item['id_jenis_barang']; ?>">
                                                <input type="text" class="inp-custom inp-readonly"
                                                    value="<?= $item['sub_barang']; ?>" readonly>
                                                <i class="fa-solid fa-check icon-inside"
                                                    style="color: #22c55e; font-size: 18px;"></i>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <label class="lbl">Jumlah</label>
                                            <input type="number" name="jumlah_peminjaman[]" class="inp-custom" min="1"
                                                value="<?= $curr_jml; ?>" required style="text-align: center;">
                                        </div>

                                        <div class="col-md-5">
                                            <label class="lbl">Pilih Spesifikasi</label>
                                            <div class="icon-wrap">
                                                <select name="unit_selected[]" class="inp-custom" required>
                                                    <option value="">-- Pilih Spesifikasi --</option>

                                                    <?php if (!empty($item['list_unit'])): ?>
                                                        <?php foreach ($item['list_unit'] as $spec): ?>
                                                            <option value="<?= $spec['id_spesifikasi']; ?>"
                                                                <?= ($curr_unit == $spec['id_spesifikasi']) ? 'selected' : ''; ?>>

                                                                <?= $spec['spesifikasi_barang']; ?>

                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <option value="" disabled>Tidak ada spesifikasi tersedia</option>
                                                    <?php endif; ?>
                                                </select>
                                                <i class="fa-solid fa-caret-down icon-inside" style="color: #1e293b;"></i>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            <?php endforeach; ?>

                        <?php else: ?>
                            <div class="alert alert-warning text-center">
                                <i class="fas fa-exclamation-triangle"></i> Data barang kosong. Silakan tambah barang.
                            </div>
                        <?php endif; ?>

                        <div class="add-more-container" style="margin-top: 20px; text-align: center;">
                            <button type="button" onclick="submitDraft()" class="btn btn-primary btn-safe-action"
                                title="Tambah Barang Lain" style="border-radius: 50px; padding: 10px 20px;">
                                <i class="fa-solid fa-plus"></i> Tambah Barang
                            </button>
                        </div>
                    </div>
                </div>

                <div class="action-footer">
                    <?php if ($isEdit): ?>
                        <a href="<?= BASEURL; ?>Riwayat" class="btn-back">Batal Edit</a>
                    <?php else: ?>
                        <a href="<?= BASEURL; ?>Peminjaman" class="btn-back">Kembali</a>
                    <?php endif; ?>

                    <?php if (!empty($data['barang_selected'])): ?>
                        <button type="submit" class="btn-send" id="btnSubmitPeminjaman">
                            <?= $isEdit ? 'Simpan Perubahan' : 'Kirim'; ?>
                        </button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modalHapus" class="custom-modal-overlay">
    <div class="custom-modal-box">
        <div class="modal-icon-circle"><i class="fa-regular fa-trash-can"></i></div>
        <h3 class="modal-title">Hapus Item?</h3>
        <p class="modal-desc">Apakah Anda yakin ingin menghapus barang ini<br>dari daftar peminjaman?</p>
        <div class="modal-btn-group">
            <button type="button" onclick="tutupModal()" class="btn-modal-cancel">Batal</button>
            <a id="btnLinkHapus" href="#" class="btn-modal-delete">Ya, Hapus</a>
        </div>
    </div>
</div>

<script>
    function toggleTujuanDetail(value) {
        // Reset all
        document.getElementById('wrap_tujuan_ta').style.display = 'none';
        document.getElementById('tujuan_ta').removeAttribute('required');
        document.getElementById('wrap_tujuan_riset').style.display = 'none';
        document.getElementById('tujuan_riset').removeAttribute('required');
        document.getElementById('wrap_tujuan_lain').style.display = 'none';
        document.getElementById('tujuan_lain').removeAttribute('required');
        document.getElementById('wrap_dosen').style.display = 'none';
        document.getElementById('dosen_pembimbing').removeAttribute('required');

        if (value === 'Tugas Akhir') {
            document.getElementById('wrap_tujuan_ta').style.display = 'block';
            document.getElementById('tujuan_ta').setAttribute('required', 'required');
            document.getElementById('wrap_dosen').style.display = 'block';
            document.getElementById('dosen_pembimbing').setAttribute('required', 'required');
        } else if (value === 'Riset') {
            document.getElementById('wrap_tujuan_riset').style.display = 'block';
            document.getElementById('tujuan_riset').setAttribute('required', 'required');
            document.getElementById('wrap_dosen').style.display = 'block';
            document.getElementById('dosen_pembimbing').setAttribute('required', 'required');
        } else if (value === 'Lain-lain') {
            document.getElementById('wrap_tujuan_lain').style.display = 'block';
            document.getElementById('tujuan_lain').setAttribute('required', 'required');
            document.getElementById('wrap_dosen').style.display = 'block';
            document.getElementById('dosen_pembimbing').setAttribute('required', 'required');
        }
    }

    function submitDraft() {
        const form = document.getElementById('formPeminjaman');
        // Ubah action sementara ke simpanDraft
        const originalAction = form.action;
        form.action = '<?= BASEURL; ?>Peminjaman/simpanDraft';

        // Remove 'required' temporarily if needed, but it's better to keep it
        // so user fills header first. However if they just want to add items,
        // we might allow empty headers? User said "inputan tidak hilang", 
        // implying they WANT to keep what's there.

        form.submit();
    }

    function updateReturnDateConstraints() {
        const tglMulai = document.getElementById('tanggal_peminjaman');
        const tglSampai = document.getElementById('tanggal_pengembalian');
        const wrapper = document.getElementById('return_date_wrapper');

        if (tglMulai.value) {
            // UNLOCK
            if (wrapper) wrapper.classList.remove('is-locked');
            
            // Set min untuk tanggal sampai (minimal sama dengan tanggal mulai)
            tglSampai.min = tglMulai.value;
            tglSampai.disabled = false;
            tglSampai.style.opacity = "1";
            tglSampai.style.cursor = "pointer";

            // Hitung max (2 bulan dari tanggal mulai)
            let dateObj = new Date(tglMulai.value);
            dateObj.setMonth(dateObj.getMonth() + 2);

            // Format YYYY-MM-DD
            let y = dateObj.getFullYear();
            let m = String(dateObj.getMonth() + 1).padStart(2, '0');
            let d = String(dateObj.getDate()).padStart(2, '0');
            tglSampai.max = `${y}-${m}-${d}`;

            // Reset jika tanggal sampai sebelumnya sudah terpilih tapi di luar range baru
            if (tglSampai.value && (tglSampai.value < tglSampai.min || tglSampai.value > tglSampai.max)) {
                tglSampai.value = "";
            }
        } else {
            // LOCK
            if (wrapper) wrapper.classList.add('is-locked');
            
            tglSampai.disabled = true;
            tglSampai.value = "";
            tglSampai.style.opacity = "0.6";
            tglSampai.style.cursor = "not-allowed";
        }
    }

    // Jalankan saat load untuk menangani data draf
    document.addEventListener('DOMContentLoaded', function () {
        updateReturnDateConstraints();
    });

    const formPeminjaman = document.getElementById('formPeminjaman');

    if (formPeminjaman) {
        formPeminjaman.addEventListener('submit', function (e) {
            var btn = document.getElementById('btnSubmitPeminjaman');
            if (btn) {
                // Ubah teks tombol jadi loading
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
                btn.style.opacity = '0.7';
                btn.style.pointerEvents = 'none'; // Cegah klik ganda
            }
        });
    }

    // Cek apakah ada data Flash dari Controller
    <?php if (isset($_SESSION['flash'])): ?>
        Swal.fire({
            title: "<?= $_SESSION['flash']['pesan']; ?>",
            html: "<?= $_SESSION['flash']['aksi']; ?>",
            icon: "<?= $_SESSION['flash']['tipe']; ?>",
            confirmButtonColor: '#1250ba',
            confirmButtonText: 'Oke, Saya Cek Lagi'
        });
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    function konfirmasiHapus(url) {
        document.getElementById('btnLinkHapus').href = url;
        document.getElementById('modalHapus').style.display = 'flex';
    }

    function tutupModal() {
        document.getElementById('modalHapus').style.display = 'none';
    }

    document.addEventListener("DOMContentLoaded", function () {

        const isEditMode = <?= $isEdit ? 'true' : 'false'; ?>;
        const hasItems = <?= !empty($data['barang_selected']) ? 'true' : 'false'; ?>;

        if (isEditMode || hasItems) {
            document.body.addEventListener('click', function (e) {
                const link = e.target.closest('a');
                if (!link) return;

                const targetUrl = link.getAttribute('href');

                // Filter link yang aman (tidak perlu dicegat)
                if (!targetUrl || targetUrl === '#' || targetUrl.startsWith('javascript')) return;
                if (link.hasAttribute('data-toggle') || link.hasAttribute('data-target')) return;
                if (link.id === 'btnLinkHapus' || link.classList.contains('btn-modal-delete')) return;
                if (targetUrl.includes('hapusItem')) return;
                if (link.classList.contains('btn-safe-action')) return;
                if (targetUrl === '<?= BASEURL; ?>Peminjaman' || targetUrl === '<?= BASEURL; ?>Peminjaman/') return;

                // --- CEGAT NAVIGASI ---
                e.preventDefault();
                e.stopImmediatePropagation();

                let swalTitle, swalText, btnConfirmText, btnDenyText, denyUrl;

                if (isEditMode) {
                    swalTitle = 'Keluar dari Edit Mode?';
                    swalText = 'Perubahan yang belum disimpan akan hilang. Simpan sekarang atau batalkan edit?';
                    btnConfirmText = 'Simpan Perubahan';
                    btnDenyText = 'Batal Edit';
                    denyUrl = '<?= BASEURL; ?>Peminjaman/batalEdit';
                } else {
                    swalTitle = 'Belum Mengajukan Barang!';
                    swalText = 'Anda memiliki barang di daftar. Ingin ajukan sekarang atau hapus daftar?';
                    btnConfirmText = 'Ajukan Sekarang';
                    btnDenyText = 'Hapus Daftar';
                    denyUrl = '<?= BASEURL; ?>Peminjaman/batal';
                }

                Swal.fire({
                    title: swalTitle,
                    text: swalText,
                    icon: 'warning',
                    width: '700px',
                    showCancelButton: true,
                    showDenyButton: true,
                    showConfirmButton: true,
                    confirmButtonText: btnConfirmText,
                    denyButtonText: btnDenyText,
                    cancelButtonText: 'Kembali',
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'btn-send px-4 py-2',
                        denyButton: 'btn-back px-4 py-2',
                        cancelButton: 'btn-back px-4 py-2',
                        actions: 'gap-2 mt-3'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // KIRIM FORM
                        const form = document.getElementById('formPeminjaman');
                        if (form) form.submit();
                    } else if (result.isDenied) {
                        // RESET & LANJUT
                        fetch(denyUrl).then(() => {
                            window.location.href = targetUrl;
                        });
                    }
                });
            }, true);
        }
    });
</script>