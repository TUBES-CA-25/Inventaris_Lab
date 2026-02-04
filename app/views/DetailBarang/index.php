<?php
if (!isset($_SESSION['login'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}
?>



<div class="content">
    <div class="content-beranda">
        <h1 id="title">Detail Barang</h1>
        <div class="flash">
            <?php Flasher::flash(); ?>
        </div>

        <div class="top-action-bar">
            <div class="left-buttons">
                <?php if (isset($_SESSION['login']) && in_array($_SESSION['id_role'], ['1', '2', '3', '4'])): ?>
                    <a href="<?= BASEURL; ?>DetailBarang/tambah" class="btn-custom-tambah">
                        <i class="fa-solid fa-plus"></i> Tambah
                    </a>
                <?php endif; ?>

                <button type="button" onclick="submitExport()" class="btn-custom-export">
                    <i class="fa-solid fa-file-export"></i> Ekspor
                </button>

                <button type="button" class="btn-custom-filter" onclick="toggleFilter()" title="Buka Filter">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
            </div>

            <div class="search-box">
                <form action="<?= BASEURL ?>DetailBarang/cari" method="POST" style="display: flex; align-items: center; width: 100%;">
                    <i class="fa-solid fa-magnifying-glass " style="margin-left: 10px;"></i>
                    <input type="text" name="keyword" id="customSearch" class="ml-3 search-input" placeholder="Cari barang..." value="<?= isset($_POST['keyword']) ? $_POST['keyword'] : '' ?>">
                    <button type="submit" style="display: none;"></button>
                </form>
            </div>
        </div>

        <div id="filterSection" class="card p-3 mb-3" style="border-radius: 10px;">
            <h6 style="color: var(--primary-blue); margin-bottom: 10px; font-weight: 600;">Filter Data</h6>
            <form method="POST" action="">
                <div class="row g-2">
                    <div class="col-12 col-md-4">
                        <select name="lokasi" onchange="this.form.submit()" class="form-select form-select-sm">
                            <option value="">Semua Lokasi</option>
                            <?php foreach ($data['lokasiPenyimpanan'] ?? [] as $lokasi): ?>
                                <option value="<?= $lokasi['id_lokasi_penyimpanan'] ?>" <?= isset($_POST['lokasi']) && $_POST['lokasi'] == $lokasi['id_lokasi_penyimpanan'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($lokasi['nama_lokasi_penyimpanan']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-12 col-md-4">
                        <select name="sub_barang" onchange="this.form.submit()" class="form-select form-select-sm">
                            <option value="">Semua Jenis</option>
                            <?php foreach ($data['sub_barang'] ?? [] as $sub): ?>
                                <option value="<?= $sub['id_jenis_barang'] ?>" <?= isset($_POST['sub_barang']) && $_POST['sub_barang'] == $sub['id_jenis_barang'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($sub['sub_barang']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-12 col-md-4">
                        <select name="merek_barang" onchange="this.form.submit()" class="form-select form-select-sm">
                            <option value="">Semua Merek</option>
                            <?php foreach ($data['nama_merek_barang'] ?? [] as $merek): ?>
                                <option value="<?= $merek['id_merek_barang'] ?>" <?= isset($_POST['merek_barang']) && $_POST['merek_barang'] == $merek['id_merek_barang'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($merek['nama_merek_barang']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <form id="formCetak" action="<?= BASEURL ?>DetailBarang/cetak" method="post" target="_blank">
                <table id="myTable" class="table table-hover" style="width:100%; margin-bottom: 0;">
                    <thead class="table-custom-header">
                        <tr>
                            <th class="p-3 text-center" style="width: 50px;">
                                <input type="checkbox" id="selectAll" class="custom-checkbox">
                            </th>
                            <th class="p-3">Kode Barang</th>
                            <th class="p-3">Jenis</th>
                            <th class="p-3">Merek</th>
                            <th class="p-3">Spesifikasi</th>
                            <th class="p-3 text-center">Jml</th>
                            <th class="p-3 text-center">Satuan</th>
                            <th class="p-3 text-center">Status</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        <?php foreach ($data['dataTampilBarang'] as $row): ?>
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" name="id_barang[]"
                                        value="<?= IdObfuscator::encode($row['id_barang']) ?>"
                                        class="custom-checkbox item-checkbox">
                                </td>
                                <td style="font-weight:600;"><?= $row['kode_barang'] . '/' . $row['jumlah_total']; ?></td>
                                <td style="text-transform: capitalize;"><?= $row['sub_barang']; ?></td>
                                <td style="text-transform: capitalize;"><?= $row['nama_merek_barang']; ?></td>
                                <td><?= !empty($row['spesifikasi_barang']) ? $row['spesifikasi_barang'] : '-'; ?></td>
                                <td class="text-center"><?= $row['jumlah_total'] ?? '0'; ?></td>
                                <td class="text-center"><?= $row['nama_satuan'] ?? '-'; ?></td>

                                <td class="text-center">
                                    <?php
                                    $statusClass = 'bg-secondary';
                                    if (strtolower($row['kondisi_barang']) == 'bagus' || strtolower($row['kondisi_barang']) == 'baik') {
                                        $statusClass = 'bg-bagus';
                                    } elseif (strtolower($row['kondisi_barang']) == 'rusak') {
                                        $statusClass = 'bg-rusak';
                                    }
                                    ?>
                                    <span class="badge-status <?= $statusClass; ?>">
                                        <?= $row['kondisi_barang']; ?>
                                    </span>
                                </td>

                                <td class="text-center">
                                    <div style="display: flex; justify-content: center; gap: 10px;">
                                        <?php if (isset($_SESSION['login']) && in_array($_SESSION['id_role'], ['1', '2', '3', '4'])): ?>
                                            <!-- <a href="<?= BASEURL; ?>DetailBarang/ubah/<?= IdObfuscator::encode($item['id_barang']); ?>"
                                                data-toggle="modal" data-target="#modalTambah"
                                                data-id="<?= IdObfuscator::encode($row['id_barang']); ?>">
                                                <i class="fa-regular fa-pen-to-square fa-lg"
                                                    style="color: var(--accent-green);"></i>
                                            </a> -->
                                            <a data-bs-toggle="modal"
                                                data-bs-target="#konfirmasiHapus<?= IdObfuscator::encode($row['id_barang']) ?>"
                                                style="cursor: pointer;">
                                                <i class="fa-regular fa-trash-can fa-lg" style="color: var(--accent-red);"></i>
                                            </a>
                                        <?php endif; ?>
                                        <a href="<?= BASEURL; ?>DetailBarang/detail/<?= IdObfuscator::encode($row['id_barang']); ?>"
                                            title="Lihat Detail">
                                            <i class="fa-solid fa-circle-info fa-lg" style="color: #1250ba;"></i>
                                        </a>
                                    </div>

                                    <div class="modal fade"
                                        id="konfirmasiHapus<?= IdObfuscator::encode($row['id_barang']) ?>" tabindex="-1"
                                        role="dialog">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content" style="border-radius: 15px;">
                                                <div class="modal-body" style="text-align: center;">
                                                    <lottie-player
                                                        src="https://lottie.host/482b772b-9f0c-4065-b54d-dcc81da3b212/Dmb3I1o98u.json"
                                                        background="transparent" speed="1"
                                                        style="width: 200px; height: 200px; margin: 0 auto;" loop
                                                        autoplay></lottie-player>
                                                    <p>Apakah anda yakin ingin menghapus item ini?</p>
                                                </div>
                                                <div class="modal-footer justify-content-center">
                                                    <button type="button" class="btn btn-light"
                                                        data-bs-dismiss="modal">Batal</button>
                                                    <button type="button" class="btn btn-danger"
                                                        onclick="location.href='<?= BASEURL ?>DetailBarang/hapus/<?= IdObfuscator::encode($row['id_barang']) ?>'">Hapus</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- <div class="modal fade" id="modalDetail<?= IdObfuscator::encode($row['id_barang']); ?>"
                                        tabindex="-1" role="dialog">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content" style="border-radius: 15px;">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Detail Barang</h5>
                                                    <button type="button" class="close"
                                                        data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row g-3">
                                                        <div class="col-12 col-sm-6">
                                                            <img src="<?= BASEURL . $row['foto_barang']; ?>"
                                                                class="img-fluid"
                                                                style="width:150px; height:150px; object-fit:cover; margin-bottom:10px;">
                                                            <p><strong>Kode:</strong> <?= $row['kode_barang']; ?></p>
                                                            <p><strong>Merek:</strong> <?= $row['nama_merek_barang']; ?></p>
                                                        </div>
                                                        <div class="col-12 col-sm-6">
                                                            <img src="<?= BASEURL . $row['qr_code'] ?>"
                                                                style="width:150px; height:150px;">
                                                            <p><strong>Kondisi:</strong> <?= $row['kondisi_barang']; ?></p>
                                                            <p><strong>Lokasi:</strong>
                                                                <?= $row['nama_lokasi_penyimpanan']; ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>