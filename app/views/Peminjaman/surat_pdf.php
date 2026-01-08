<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Peminjaman Alat</title>
    <style>
        /* Margin Kertas A4 (Atas, Kanan, Bawah, Kiri) */
        @page { margin: 10px 2.5cm 2cm 2.5cm; }
        
        body { 
            font-family: 'Times New Roman', Times, serif; 
            font-size: 12pt; 
            line-height: 1.3;
            color: #000;
            margin: 0;
            padding: 0;
        }

        /* --- KOP SURAT --- */
        .header-kop {
            width: 100%;
            text-align: center;
            margin-top: 10px;
            margin-bottom: 5px;
            /* Garis ganda di bawah kop */
            border-bottom: 3px double black; 
            padding-bottom: 5px;
        }
        .header-kop img {
            width: 100%; /* Gambar otomatis menyesuaikan lebar */
            max-height: 120px; /* Batasi tinggi agar tidak terlalu besar */
            object-fit: contain;
        }

        /* --- JUDUL --- */
        .judul-surat {
            text-align: center;
            font-weight: bold;
            font-size: 14pt;
            margin-top: 15px;
            margin-bottom: 15px;
        }

        /* --- PARAGRAF & TEXT --- */
        .paragraph {
            text-align: justify;
            margin-bottom: 8px;
        }
        
        /* Tabel Biodata (Agar titik dua sejajar rapi) */
        .table-info {
            width: 100%;
            margin-left: 15px; /* Indentasi seperti tombol Tab */
            margin-bottom: 10px;
        }
        .table-info td {
            vertical-align: top;
            padding: 2px 0;
        }
        .col-label { width: 220px; }
        .col-sep { width: 15px; text-align: center; }

        /* --- TABEL BARANG --- */
        .table-barang {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .table-barang th, .table-barang td {
            border: 1px solid black;
            padding: 5px 8px;
            font-size: 11pt;
        }
        .table-barang th {
            background-color: #f9f9f9;
            text-align: center;
            font-weight: bold;
        }
        .text-center { text-align: center; }

        /* --- TANDA TANGAN (SESUAI DOCX) --- */
        .signature-wrapper {
            margin-top: 30px;
        }

        /* Baris pertama: Yang Menyerahkan (KIRI) & Makassar + Yang Menyatakan (KANAN) */
        .signature-table {
            width: 100%;
            border: none;
            border-collapse: collapse;
        }
        
        .signature-table td {
            vertical-align: top;
            padding: 0;
        }
        
        /* Kolom Kiri: Yang Menyerahkan */
        .col-left {
            width: 50%;
            text-align: left;
            padding-right: 20px;
        }
        
        /* Kolom Kanan: Tanggal + Yang Menyatakan */
        .col-right {
            width: 50%;
            text-align: right;
            padding-left: 20px;
        }
        
        /* Ruang tanda tangan */
        .ttd-space {
            height: 65px;
        }
        
        /* Nama dengan garis bawah */
        .underline-name {
            text-decoration: underline;
            font-weight: normal;
        }
        
        /* Section Menyetujui di tengah */
        .approval-section {
            text-align: center;
            margin-top: 20px;
        }
    </style>
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

    <div class="judul-surat" style="text-decoration: underline;">FORM PEMINJAMAN ALAT</div>

    <div class="paragraph">
        Assalamu'alaikum Warahmatullahi Wabarakatuh.<br>
        Dengan Hormat,<br>
        Saya yang bertanda tangan di bawah ini :
    </div>

    <table class="table-info">
        <tr>
            <td class="col-label">Nama</td>
            <td class="col-sep">:</td>
            <td><b><?= $user['nama_user']; ?></b></td>
        </tr>
        <tr>
            <td>No Identitas (Stambuk/NIPS)</td>
            <td>:</td>
            <td><?= isset($user['nim']) ? $user['nim'] : '-'; ?></td>
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
                <th width="10%">No.</th>
                <th width="35%">Nama Alat</th>
                <th width="35%">Spesifikasi</th>
                <th width="20%">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach($details as $item): ?>
            <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td style="text-align: left;"><?= $item['nama_barang'] ?></td>
                <td style="text-align: left;">-</td>
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

    <!-- BAGIAN TANDA TANGAN SESUAI DOCX -->
    <div class="signature-wrapper">
        
        <!-- Baris 1: Yang Menyerahkan (Kiri) & Tanggal + Yang Menyatakan (Kanan) -->
        <table class="signature-table">
            <tr>
                <td class="col-left" style="vertical-align: bottom;">
                    <b>Yang Menyerahkan,</b>
                    <div class="ttd-space"></div>
                    <span class="underline-name">Fatimah A.R Tuasamu, S.Kom., MTA., MOS</span>
                </td>
                <td class="col-right">
                    Makassar, <?= date('d F Y'); ?><br>
                    <b>Yang Menyatakan,</b>
                    <div class="ttd-space"></div>
                    <span class="underline-name">( <?= $user['nama_user']; ?> )</span>
                </td>
            </tr>
        </table>

        <!-- Baris 2: Menyetujui (Center) -->
        <div class="approval-section">
            Menyetujui,<br><br>
            <b>Kepala Laboratorium Terpadu,</b>
            <div class="ttd-space"></div>
            <span class="underline-name">Ir. Huzain Azis, S.Kom., M.Cs., MTA</span><br>
            NIDN : 0920098801
        </div>
    </div>

</body>
</html>