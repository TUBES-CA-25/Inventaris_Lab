<?php
if (!isset($_SESSION['login'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}
?>

<div class="modal fade" id="konfirmasiKeluar" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 15px;">
            <div class="modal-body" style="text-align: center;">
                <lottie-player src="https://lottie.host/48c004f8-57cd-4acb-a04a-de46793ba7dc/jUGVFL9qIO.json" background="transparent" speed="1" style="width: 250px; height: 250px; margin: 0 auto;" loop autoplay></lottie-player>
                <p style="color:#385161; opacity: 0.6; font-weight: 500;">Apakah anda yakin ingin keluar?</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-light" style="width: 100px;" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" style="width: 100px;" onclick="location.href='<?= BASEURL; ?>Logout'">Keluar</button>
            </div>
        </div>
    </div>
</div>

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
            </div>

            <div class="search-container">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="text" id="customSearch" class="search-input" placeholder="Cari barang...">
                <i class="fa-solid fa-filter filter-trigger" onclick="toggleFilter()" title="Buka Filter"></i>
            </div>
        </div>

        <div id="filterSection">
            <h6 style="color: var(--primary-blue); margin-bottom: 10px; font-weight: 600;">Filter Data</h6>
            <form method="POST" action="">
                <select name="lokasi" onchange="this.form.submit()" class="custom-select">
                    <option value="">Semua Lokasi</option>
                    <?php foreach ($data['lokasiPenyimpanan'] ?? [] as $lokasi): ?>
                        <option value="<?= $lokasi['id_lokasi_penyimpanan'] ?>" <?= isset($_POST['lokasi']) && $_POST['lokasi'] == $lokasi['id_lokasi_penyimpanan'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($lokasi['nama_lokasi_penyimpanan']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <select name="sub_barang" onchange="this.form.submit()" class="custom-select">
                    <option value="">Semua Jenis</option>
                    <?php foreach ($data['sub_barang'] ?? [] as $sub): ?>
                        <option value="<?= $sub['id_jenis_barang'] ?>" <?= isset($_POST['sub_barang']) && $_POST['sub_barang'] == $sub['id_jenis_barang'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($sub['sub_barang']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <select name="merek_barang" onchange="this.form.submit()" class="custom-select">
                    <option value="">Semua Merek</option>
                    <?php foreach ($data['nama_merek_barang'] ?? [] as $merek): ?>
                        <option value="<?= $merek['id_merek_barang'] ?>" <?= isset($_POST['merek_barang']) && $_POST['merek_barang'] == $merek['id_merek_barang'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($merek['nama_merek_barang']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>

        <div class="table-container">
            <form id="formCetak" action="<?= BASEURL ?>DetailBarang/cetak" method="post" target="_blank">
                <table id="myTable" class="table table-hover" style="width:100%; margin-bottom: 0;">
                    <thead class="table-custom-header">
                        <tr>
                            <th class="p-3 text-center" style="width: 50px;">
                                <input type="checkbox" id="selectAll" class="custom-checkbox">
                            </th>
                            <th class="p-3 text-center">No</th>
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
                                    <input type="checkbox" name="id_barang[]" value="<?= $row['id_barang'] ?>" class="custom-checkbox item-checkbox">
                                </td>
                                <td class="text-center"><?= $i++; ?></td>
                                <td style="font-weight:600;"><?= $row['kode_barang']; ?></td>
                                <td style="text-transform: capitalize;"><?= $row['sub_barang']; ?></td>
                                <td style="text-transform: capitalize;"><?= $row['nama_merek_barang']; ?></td>
                                <td><?= !empty($row['spesifikasi_barang']) ? $row['spesifikasi_barang'] : '-'; ?></td>
                                <td class="text-center"><?= $row['jumlah_barang'] ?? '0'; ?></td>
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
                                            <a href="<?= BASEURL ?>DetailBarang/getUbah/<?= $row['id_barang'] ?>"
                                                data-toggle="modal" data-target="#modalTambah" data-id="<?= $row['id_barang']; ?>">
                                                <i class="fa-regular fa-pen-to-square fa-lg" style="color: var(--accent-green);"></i>
                                            </a>
                                            <a data-toggle="modal" data-target="#konfirmasiHapus<?= $row['id_barang'] ?>" style="cursor: pointer;">
                                                <i class="fa-regular fa-trash-can fa-lg" style="color: var(--accent-red);"></i>
                                            </a>
                                        <?php endif; ?>
                                        <a href="<?= BASEURL; ?>DetailBarang/detail/<?= $row['id_barang']; ?>" title="Lihat Detail">
                                            <i class="fa-solid fa-circle-info fa-lg" style="color: #1250ba;"></i>
                                        </a>
                                    </div>

                                    <div class="modal fade" id="konfirmasiHapus<?= $row['id_barang'] ?>" tabindex="-1" role="dialog">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content" style="border-radius: 15px;">
                                                <div class="modal-body" style="text-align: center;">
                                                    <lottie-player src="https://lottie.host/482b772b-9f0c-4065-b54d-dcc81da3b212/Dmb3I1o98u.json" background="transparent" speed="1" style="width: 200px; height: 200px; margin: 0 auto;" loop autoplay></lottie-player>
                                                    <p>Apakah anda yakin ingin menghapus item ini?</p>
                                                </div>
                                                <div class="modal-footer justify-content-center">
                                                    <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                                                    <button type="button" class="btn btn-danger" onclick="location.href='<?= BASEURL ?>DetailBarang/hapus/<?= $row['id_barang'] ?>'">Hapus</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="modalDetail<?= $row['id_barang']; ?>" tabindex="-1" role="dialog">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content" style="width: 700px; padding: 20px;">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Detail Barang</h5>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <img src="<?= BASEURL . $row['foto_barang']; ?>" style="width:150px; height:150px; object-fit:cover; margin-bottom:10px;">
                                                            <p><strong>Kode:</strong> <?= $row['kode_barang']; ?></p>
                                                            <p><strong>Merek:</strong> <?= $row['nama_merek_barang']; ?></p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <img src="<?= BASEURL . $row['qr_code'] ?>" style="width:150px; height:150px;">
                                                            <p><strong>Kondisi:</strong> <?= $row['kondisi_barang']; ?></p>
                                                            <p><strong>Lokasi:</strong> <?= $row['nama_lokasi_penyimpanan']; ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </form>
        </div>

        <div class="modal fade" id="modalTambah" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content" style="height: 100%;width:900px; border-radius:15px">
                    <div class="modal-header">
                        <h5 class="modal-title" id="title-barang">Tambah Barang</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body body-barang">
                        <form action="<?= BASEURL ?>DetailBarang/tambahBarang" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="id_barang" id="id_barang">
                            <div style="display: flex; width:100%; gap:20%;">
                                <div style="margin-top: 8px;">
                                    <div class="foto_barang">
                                        <input type="file" name="foto_barang" id="foto_barang" accept="image/*" onchange="limitSize()" />
                                        <label for="foto_barang">Upload Foto (Maks 2 MB) </label>
                                    </div>
                                    <br>
                                    <div class="sub_barang">
                                        <label for="sub_barang">Sub barang</label><br>
                                        <select name="sub_barang" id="sub_barang" style="width: 250px;" required>
                                            <option value="">-- Pilih --</option>
                                            <?php foreach ($data['sub_barang'] as $option) { ?>
                                                <option value="<?= $option['id_jenis_barang'] ?>"><?= $option['sub_barang'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <br>
                                    <div class="nama_merek_barang">
                                        <label for="nama_merek_barang">Merek barang</label><br>
                                        <select name="nama_merek_barang" id="nama_merek_barang" style="width: 250px;" required>
                                            <option value="">-- Pilih --</option>
                                            <?php foreach ($data['nama_merek_barang'] as $option) { ?>
                                                <option value="<?= $option['id_merek_barang'] ?>"><?= $option['nama_merek_barang'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <br>
                                    <div class="spesifikasi_barang">
                                        <label for="spesifikasi_barang">Spesifikasi barang</label><br>
                                        <input type="text" name="spesifikasi_barang" id="spesifikasi_barang" style="width: 250px;">
                                    </div>
                                    <br>
                                    <div class="jumlah_barang">
                                        <label for="jumlah_barang">Jumlah barang</label><br>
                                        <input type="number" name="jumlah_barang" id="jumlah_barang" style="width: 250px; text-align: center;" min="1" value="1" required>
                                    </div>
                                    <br>
                                    <div class="satuan">
                                        <label for="satuan">Satuan</label><br>
                                        <select name="satuan" id="satuan" required style="width: 250px;">
                                            <option value="">-- Pilih --</option>
                                            <?php foreach ($data['satuan'] as $option) { ?>
                                                <option value="<?= $option['id_satuan']; ?>"><?= $option['nama_satuan']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <br>
                                    <div class="barang_ke">
                                        <label for="barang_ke">Barang ke-</label><br>
                                        <input type="number" name="barang_ke" id="barang_ke" style="width: 250px;" min="0" required>
                                    </div>
                                    <br>
                                    <div class="total_barang">
                                        <label for="total_barang">Total barang</label><br>
                                        <input type="number" name="total_barang" id="total_barang" style="width: 250px;" min="0" required>
                                    </div>
                                </div>
                                <div style="margin-top: 8px;">
                                    <div class="tgl_pengadaan_barang">
                                        <label for="tgl_pengadaan_barang">Tgl pengadaan</label><br>
                                        <input type="date" name="tgl_pengadaan_barang" id="tgl_pengadaan_barang" style="width: 250px;" required>
                                    </div>
                                    <br>
                                    <div class="lokasi_penyimpanan">
                                        <label for="lokasi_penyimpanan">Lokasi penyimpanan</label><br>
                                        <select name="lokasi_penyimpanan" id="lokasi_penyimpanan" required style="width: 250px;">
                                            <option value="">-- Pilih --</option>
                                            <?php foreach ($data['lokasiPenyimpanan'] as $option) { ?>
                                                <option value="<?= $option['id_lokasi_penyimpanan']; ?>"><?= $option['nama_lokasi_penyimpanan']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <br>
                                    <div class="deskripsi_detail_lokasi">
                                        <label for="deskripsi_detail_lokasi">Detail penyimpanan</label><br>
                                        <input type="text" name="deskripsi_detail_lokasi" id="deskripsi_detail_lokasi" style="width: 250px;">
                                    </div>
                                    <br>
                                    <div class="status" style="margin-top: 10px;">
                                        <label for="status">Status</label><br>
                                        <select name="status" id="status" required style="width: 250px;">
                                            <option value="">-- Pilih --</option>
                                            <?php foreach ($data['status'] as $option) { ?>
                                                <option value="<?= $option['id_status']; ?>"><?= $option['status']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <br>
                                    <div class="kondisi_barang">
                                        <label for="kondisi_barang">Kondisi barang</label><br>
                                        <select name="kondisi_barang" id="kondisi_barang" required style="width: 250px;">
                                            <option value="">-- Pilih --</option>
                                            <?php foreach ($data['kondisiBarang'] as $option) { ?>
                                                <option value="<?= $option['id_kondisi_barang']; ?>"><?= $option['kondisi_barang']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <br>
                                    <div class="status_pinjam">
                                        <label for="status_pinjam">Status pinjam</label><br>
                                        <select name="status_pinjam" id="status_pinjam" style="width: 250px; margin-top: 5px;">
                                            <option value="">-- Pilih --</option>
                                            <option value="bisa">Bisa</option>
                                            <option value="tidak bisa">Tidak bisa</option>
                                        </select>
                                    </div>
                                    <br>
                                    <div class="keterangan_label" style="margin-top:5px">
                                        <label for="keterangan_label">Keterangan label</label><br>
                                        <select name="keterangan_label" id="keterangan_label" required style="width: 250px; margin-top: 5px;">
                                            <option value="">-- Pilih --</option>
                                            <option value="sudah">Sudah</option>
                                            <option value="belum">Belum</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer" style="margin-top: 30px; justify-content: flex-end;">
                                <button type="submit" id="kirim" class="btn btn-primary" style="background-color: var(--primary-blue);">Kirim</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
