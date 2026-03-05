<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Form Peminjaman Alat Khusus Mahasiswa</title>
    <link rel="stylesheet" href="<?= BASEURL; ?>/css/SuratPDF.css?v=<?= time(); ?>">
</head>

<body>

    <div class="header-kop">
        <?php if (!empty($gambar_kop)): ?>
            <img src="<?= $gambar_kop; ?>" alt="Kop Surat">
        <?php else: ?>
            <h3 style="margin:0;">LABORATORIUM TERPADU</h3>
            <p style="margin:0;">Fakultas Ilmu Komputer - UMI</p>
        <?php endif; ?>
    </div>

    <div class="judul-surat">FORM PEMINJAMAN ALAT KHUSUS MAHASISWA</div>

    <div class="paragraph">
        Assalamu’alaikum Warahmatullahi Wabarakatuh.<br>
        Dengan Hormat,<br>
        Saya yang bertanda tangan di bawah ini :
    </div>

    <table class="table-info">
        <tr>
            <td class="col-label">Nama Mahasiswa</td>
            <td class="col-sep">:</td>
            <td><?= isset($user['nama_user']) ? $user['nama_user'] : '-'; ?></td>
        </tr>
        <tr>
            <td>Stambuk</td>
            <td>:</td>
            <td><?= isset($user['nim_nip']) ? $user['nim_nip'] : '-'; ?></td>
        </tr>
        <tr>
            <td>No Telp</td>
            <td>:</td>
            <td><?= isset($user['no_hp_user']) ? $user['no_hp_user'] : '-'; ?></td>
        </tr>
    </table>

    <div class="paragraph">
        Bermaksud meminjam alat untuk keperluan <b><?= $peminjaman['kategori_kegiatan'] ?? 'Tugas Akhir / Riset'; ?></b>
        dengan judul kegiatan:
    </div>

    <table class="table-info">
        <tr>
            <td class="col-label">Judul Kegiatan</td>
            <td class="col-sep">:</td>
            <td><?= isset($peminjaman['judul_kegiatan']) ? $peminjaman['judul_kegiatan'] : '-'; ?></td>
        </tr>
        <tr>
            <td>Waktu Peminjaman</td>
            <td>:</td>
            <td>
                <?php if (isset($peminjaman['tanggal_peminjaman'])): ?>
                    <?= date('d/m/Y', strtotime($peminjaman['tanggal_peminjaman'])); ?>
                    s.d
                    <?= date('d/m/Y', strtotime($peminjaman['tanggal_pengembalian'])); ?>
                <?php else: ?>
                    -
                <?php endif; ?>
            </td>
        </tr>
    </table>

    <table class="table-barang">
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th width="30%">Nama Alat</th>
                <th width="45%">Spesifikasi</th>
                <th width="20%">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <?php if (isset($details) && !empty($details)): ?>
                <?php $no = 1;
                foreach ($details as $item): ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><?= $item['nama_barang'] ?></td>
                        <td><?= !empty($item['spesifikasi_barang']) ? $item['spesifikasi_barang'] : '-'; ?></td>
                        <td class="text-center"><?= $item['jumlah'] ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data barang</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="paragraph">
        Apabila barang yang saya pinjam mengalami kerusakan / kehilangan, maka saya bersedia bertanggungjawab
        untuk memperbaiki / mengganti barang tersebut seperti kondisi barang semula.
    </div>

    <div class="paragraph">
        Demikian surat permohonan peminjaman ini saya buat. Atas perhatian dan bantuannya saya ucapkan terima kasih.
    </div>

    <div style="margin-top: 30px;"></div>

    <div class="mhs-signature-wrapper">
        <table class="signature-table">
            <tr>
                <td class="ttd-col" style="text-align: center;">
                    Mengetahui,<br>
                    <b>Dosen Pembimbing Utama,</b>
                    <br><br><br><br>
                    <?php
                    $pembimbing = !empty($peminjaman['dosen_pembimbing']) ? $peminjaman['dosen_pembimbing'] : '...........................................';
                    $nidn = (!empty($supervisor) && !empty($supervisor['nim_nip'])) ? $supervisor['nim_nip'] : '';
                    ?>
                    ( <span
                        class="<?= $pembimbing != '...........................................' ? 'underline-name' : '' ?>"><?= $pembimbing; ?></span>
                    )
                    <?php if ($nidn): ?>
                        <br>NIDN : <?= $nidn; ?>
                    <?php endif; ?>
                </td>

                <td class="ttd-col" style="text-align: center;">
                    Makassar, <?= date('d F Y'); ?><br>
                    <b>Mahasiswa,</b>
                    <br><br><br><br>
                    <span class="underline-name"><?= $user['nama_user'] ?? '-'; ?></span>
                </td>
            </tr>
        </table>
    </div>

    <div class="approval-section">
        Menyetujui,<br>
        <b>Kepala Laboratorium Terpadu,</b>
        <br><br><br><br> <span class="underline-name">Ir. Huzain Azis, S.Kom., M.Cs., MTA</span><br>
        NIDN : 0920098801
    </div>

</body>

</html>