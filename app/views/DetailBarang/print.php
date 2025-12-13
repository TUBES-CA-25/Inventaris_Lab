<style>
    /* @media print {

        button,
        img {
            display: none;
        }

        
    } */


    button:hover {
        cursor: pointer;
        opacity: 0.9;
    }

    button {
        width: 150px;
        height: 47px;
        border-radius: 8px;
        background-color: #0c1740;
        color: white;
        font-family: "Poppins", sans-serif;
        font-weight: 500;
        font-size: 15px;
        border: none;
    }

    .buttons-excel,
    .buttons-print,
    .buttons-copy,
    .buttons-csv,
    .buttons-colvis{
        box-shadow: 5px 5px 10px 0px rgba(0, 0, 0, 0.5);
        border-radius: 8px;
        background-color: #0c1740;
        width: 170px;
        border: none;
    }

    .buttons-excel:hover,
    .buttons-print:hover,
    .buttons-csv:hover,
    .buttons-copy:hover,
    .buttons-colvis:hover{
        background-color: #0c1740;
    }

</style>

<div class="card-body p-3">
    <div class="header" style="display: flex; padding:20px;">
        <img src="<?=BASEURL;?>img/logo bg putih.svg" alt="logo" />
        <div class="button"
            style="padding-top:10px; width:100%; display:flex; align-items:end; gap:10px; flex-direction:column;">
            <button style="box-shadow: 5px 5px 10px 0px rgba(0, 0, 0, 0.5); font-weight:600;"
            onclick="location.href='<?=BASEURL?>DetailBarang'"><i class="fa-solid fa-arrow-left" style="color: #ffffff; margin-right:10px;"></i>Kembali</button>
        </div>
    </div>
    <div style="height:100%; padding:20px;box-shadow: 5px 5px 10px 0px rgba(0, 0, 0, 0.5); border-radius:10px;">
    <table id="example" class="table display nowrap" style="font-size:14px;">
        <thead class="table-info">
            <tr>
                <th scope="col" class="p-3">No.</th>
                <th scope="col" class="p-3">Qr code</th>
                <th scope="col" class="p-3">Foto</th>
                <th scope="col" class="p-3">Kode barang</th>
                <th scope="col" class="p-3">Sub barang</th>
                <th scope="col" class="p-3">Nama merek</th>
                <th scope="col" class="p-3">Deskripsi barang</th>
                <th scope="col" class="p-3">Jumlah barang</th>
                <th scope="col" class="p-3">Nama satuan</th>
                <th scope="col" class="p-3">Tgl pengadaan</th>
                <th scope="col" class="p-3">Lokasi penyimpanan</th>
                <th scope="col" class="p-3">Detail lokasi</th>
                <th scope="col" class="p-3">Keterangan label</th>
                <th scope="col" class="p-3">Kondisi barang</th>
                <th scope="col" class="p-3">Status</th>
                <th scope="col" class="p-3">Status peminjaman</th>
            </tr>
        </thead>
        <tbody>
            
        <?php $i = 1; 
    ?>
    <?php foreach ($data['dataCetak'] as $row): ?>

            <tr>
                <td scope="row" class="p-3"><?= $i++; ?></td>
                <td class="p-3"><img class="qr" src="<?=BASEURL . $row[0]['qr_code']; ?>" style="width:100px;height:100px;" alt=""></td>
                <td class="p-3"><img src="<?=BASEURL . $row[0]['foto_barang']; ?>" style="width:100px;height:100px;" alt=""></td>
                <td class="p-3"><?= $row[0]['kode_barang']; ?></td>
                <td class="p-3" style="text-transform: capitalize;"><?= $row[0]['sub_barang']; ?></td>
                <td class="p-3" style="text-transform: capitalize;"><?= $row[0]['nama_merek_barang']; ?></td>
                <td class="p-3" style="text-transform: capitalize;"><?= $row[0]['deskripsi_barang']; ?></td>
                <td class="p-3"><?= $row[0]['jumlah_barang']; ?></td>
                <td class="p-3"><?= $row[0]['nama_satuan']; ?></td>
                <td class="p-3"><?= $row[0]['tgl_pengadaan_barang']; ?></td>
                <td class="p-3"><?= $row[0]['nama_lokasi_penyimpanan']; ?></td>
                <td class="p-3" style="text-transform: capitalize;"><?= $row[0]['deskripsi_detail_lokasi']; ?></td>
                <td class="p-3"><?= $row[0]['keterangan_label']; ?></td>
                <td class="p-3"><?= $row[0]['kondisi_barang']; ?></td>
                <td class="p-3"><?= $row[0]['status']; ?></td>
                <td class="p-3"><?= $row[0]['status_peminjaman']; ?></td>
            </tr>
        
    <?php endforeach; ?>
</tbody>

    </table>
    </div>

</div>
