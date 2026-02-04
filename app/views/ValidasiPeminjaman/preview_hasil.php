<style>
    body {
        background-color: #e3e6f0;
        font-family: sans-serif;
    }

    .pdf-container {
        width: 100%;
        height: 75vh;
        border: 1px solid #b7b9cc;
        background: #525659;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    iframe {
        width: 100%;
        height: 100%;
        border: none;
    }
</style>


<nav class="navbar navbar-light bg-white shadow mb-4">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1 mx-auto font-weight-bold">Preview Dokumen Akhir</span>
    </div>
</nav>

<div class="container-fluid pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <div class="card shadow mb-4">
                <!-- <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Hasil Stempel</h6>
                    <span class="badge badge-success px-3 py-2">Status: Disetujui</span>
                </div> -->

                <div class="card-body">
                    <div class="alert alert-warning text-center">
                        <i class="fas fa-search mr-1"></i>
                        <strong>Cek Kembali!</strong> Pastikan posisi kedua tanda tangan sudah pas.
                    </div>

                    <div class="pdf-container">
                        <iframe src="<?= BASEURL; ?>files/surat-peminjaman/<?= $data['peminjaman']['file_surat']; ?>?t=<?= time(); ?>#toolbar=0"></iframe>
                    </div>
                </div>

                <div class="card-footer bg-white py-4">
                    <div class="row justify-content-center">
                        <div class="col-md-4 mb-2">
                            <a href="<?= BASEURL; ?>ValidasiPeminjaman/viewValidasiPosisi/<?= IdObfuscator::encode($data['peminjaman']['id_peminjaman']); ?>"
                                class="btn btn-warning btn-block font-weight-bold shadow-sm">
                                <i class="fas fa-undo-alt mr-2"></i> Edit Posisi Lagi
                            </a>
                        </div>
                        <div class="col-md-4 mb-2">
                            <a href="<?= BASEURL; ?>ValidasiPeminjaman/selesaiValidasi/<?= IdObfuscator::encode($data['peminjaman']['id_peminjaman']); ?>"
                                class="btn btn-success btn-block font-weight-bold shadow py-2">
                                <i class="fas fa-check-circle mr-2"></i> Selesai
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>