<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Form Peminjaman Alat</title>
    <link rel="stylesheet" href="<?= BASEURL; ?>/css/SuratPDF.css?v=<?= time(); ?>">
</head>

<body>

    <div class="header-kop">
        <?php if (!empty($gambar_kop)): ?>
            <img src="<?= $gambar_kop; ?>" alt="Kop Surat">
        <?php else: ?>
            <h3>LABORATORIUM TERPADU</h3>
            <p>Fakultas Ilmu Komputer - UMI</p>
        <?php endif; ?>
    </div>

    <div class="judul-surat">FORM PEMINJAMAN ALAT</div>

    <div class="paragraph">
        Assalamu'alaikum Warahmatullahi Wabarakatuh.<br>
        Dengan Hormat,<br>
        Saya yang bertanda tangan di bawah ini :
    </div>

    <table class="table-info">
        <tr>
            <td class="col-label">Nama</td>
            <td class="col-sep">:</td>
            <td><?= $user['nama_user']; ?></td>
        </tr>
        <tr>
            <td>No Identitas (Stambuk/NIPS)</td>
            <td>:</td>
            <td><?= isset($user['nim_nip']) ? $user['nim_nip'] : '-'; ?></td>
        </tr>
        <tr>
            <td>No Telp</td>
            <td>:</td>
            <td><?= $user['no_hp_user']; ?></td>
        </tr>
    </table>

    <div class="paragraph">
        Bermaksud meminjam alat untuk keperluan Penelitian / Lain-lain *) dengan judul kegiatan:
    </div>

    <table class="table-info">
        <tr>
            <td class="col-label">Judul Kegiatan</td>
            <td class="col-sep">:</td>
            <td><?= $peminjaman['judul_kegiatan']; ?></td>
        </tr>
        <tr>
            <td>Waktu Peminjaman</td>
            <td>:</td>
            <td>
                <?= date('d/m/Y', strtotime($peminjaman['tanggal_peminjaman'])); ?>
                s.d
                <?= date('d/m/Y', strtotime($peminjaman['tanggal_pengembalian'])); ?>
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
            <?php $no = 1;
            foreach ($details as $item): ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td><?= $item['nama_barang'] ?></td>
                    
                    <td>
                        <?= !empty($item['spesifikasi_barang']) ? $item['spesifikasi_barang'] : '-'; ?>
                    </td>
                    
                    <td class="text-center"><?= $item['jumlah'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="paragraph">
        Apabila barang yang saya pinjam mengalami kerusakan / kehilangan, maka saya bersedia bertanggungjawab
        untuk memperbaiki / mengganti barang tersebut seperti kondisi barang semula.
    </div>

    <div class="paragraph">
        Demikian surat permohonan peminjaman ini saya buat. Atas perhatian dan bantuannya saya ucapkan terima kasih.
    </div>

    <div class="signature-wrapper"></div>

    <table class="signature-table">
        <tr>
            <td class="ttd-col">
                <br> <b>Yang Menyerahkan,</b>
                <br><br><br><br> <span class="underline-name">Fatimah A.R Tuasamu, S.Kom., MTA., MOS</span>
            </td>

            <td class="ttd-col">
                Makassar, <?= date('d F Y'); ?><br>
                <b>Yang Menyatakan,</b>
                <br><br><br><br> <span class="underline-name"><?= $user['nama_user']; ?></span>
            </td>
        </tr>
    </table>

    <div class="approval-section">
        Menyetujui,<br>
        <b>Kepala Laboratorium Terpadu,</b>
        <br><br><br><br> <span class="underline-name">Ir. Huzain Azis, S.Kom., M.Cs., MTA</span><br>
        NIDN : 0920098801
    </div>

</body>
</html>