var qrModalInstance = null;


function showQrUnit(judul, urlGambar, fileAda) {
    // 1. Set Judul Unit
    var titleEl = document.getElementById('modalUnitTitle');
    if (titleEl) titleEl.innerText = judul;

    // 2. Ambil Elemen-elemen Modal
    var imgEl = document.getElementById('modalUnitImage');
    var boxGambar = document.getElementById('containerGambarUnit');
    var boxError = document.getElementById('containerErrorUnit');
    var btnDown = document.getElementById('modalUnitDownload');

    // 3. Reset Tampilan (Sembunyikan semua dulu)
    if (imgEl) imgEl.src = '';
    if (boxGambar) boxGambar.style.display = 'none';
    if (boxError) boxError.style.display = 'none';
    if (btnDown) btnDown.style.display = 'none';

    // 4. Logika Tampilkan Gambar atau Error
    if (fileAda) {
        // Jika file ada, tampilkan container gambar dan tombol download
        if (imgEl) imgEl.src = urlGambar;
        if (boxGambar) boxGambar.style.display = 'inline-block';

        if (btnDown) {
            btnDown.style.display = 'inline-flex';
            btnDown.href = urlGambar;
            // Buat nama file download yang rapi
            var safeName = judul.replace(/[^a-zA-Z0-9]/g, '_');
            btnDown.setAttribute('download', safeName + '.png');
        }
    } else {
        // Jika file tidak ada, tampilkan pesan error merah
        if (boxError) boxError.style.display = 'block';
    }

    // 5. Buka Modal (Bootstrap 5)
    var myModalEl = document.getElementById('modalQR');
    if (myModalEl) {
        if (typeof bootstrap !== 'undefined') {
            var modalInstance = bootstrap.Modal.getInstance(myModalEl) || new bootstrap.Modal(myModalEl);
            modalInstance.show();
        } else {
            $('#modalQR').modal('show');
        }
    }
}


function hideQrUnit() {
    if (qrModalInstance) {
        qrModalInstance.hide();
    } else {
        $('#modalQRUnit').modal('hide');
    }
}

/* --- LOGIKA PAGINATION TABEL UNIT (DETAIL BARANG) --- */
$(document).ready(function () {
    // Pastikan link pagination tidak ter-intercept oleh event listener lain
    $('.page-link').on('click', function (e) {
        // Jangan preventDefault, biarkan navigate normal
        // Hanya stop propagation agar tidak kena listener lain
        e.stopPropagation();
    });
});