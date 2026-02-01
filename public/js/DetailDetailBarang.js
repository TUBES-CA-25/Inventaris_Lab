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
$(document).ready(function() {
    // Cek apakah elemen #tableUnit ada di halaman ini
    // if ($('#tableUnit').length) {
        
    //     // Hancurkan inisialisasi lama jika ada
    //     if ($.fn.DataTable.isDataTable('#tableUnit')) {
    //         $('#tableUnit').DataTable().destroy();
    //     }

    //     // Inisialisasi DataTables
    //     $('#tableUnit').DataTable({
    //         // Konfigurasi Layout Bootstrap 5 (l = length menu, f = filter/search)
    //         "dom": '<"row"<"col-md-6"l><"col-md-6"f>>rt<"row"<"col-md-6"i><"col-md-6"p>>',
            
    //         // 1. SETTING DEFAULT (5 Baris)
    //         "pageLength": 5, 

    //         // 2. SETTING PILIHAN USER (Bisa pilih berapapun)
    //         // Baris pertama: Nilai logic (-1 artinya Semua)
    //         // Baris kedua: Teks yang muncul di dropdown
    //         "lengthMenu": [
    //             [5, 10, 25, 50, 100, -1], 
    //             [5, 10, 25, 50, 100, "Tampilkan Semua"]
    //         ],

    //         "language": {
    //             "emptyTable": "Tidak ada unit barang tersedia",
    //             "info": "Menampilkan _START_ - _END_ dari _TOTAL_ unit",
    //             "infoEmpty": "Menampilkan 0 data",
    //             "infoFiltered": "(difilter dari _MAX_ total unit)",
    //             // Custom teks untuk dropdown length
    //             "lengthMenu": "Tampilkan _MENU_ data per halaman",
    //             "search": "Cari Unit:",
    //             "zeroRecords": "Unit tidak ditemukan",
    //             "paginate": {
    //                 "first": '<i class="fa-solid fa-angles-left"></i>',
    //                 "last": '<i class="fa-solid fa-angles-right"></i>',
    //                 "next": '<i class="fa-solid fa-angle-right"></i>',
    //                 "previous": '<i class="fa-solid fa-angle-left"></i>'
    //             }
    //         },
    //         "ordering": false,
    //         "autoWidth": false,
    //         "responsive": true
    //     });
    // }
});