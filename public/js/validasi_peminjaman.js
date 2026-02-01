// Validasi Peminjaman JS Functions

function bukaFormTolak(id) {
    // Tutup semua form dulu
    const formTolak = document.getElementById('formTolakContainer');
    const formTolakPengembalian = document.getElementById('formTolakPengembalianContainer');
    
    if (formTolak) formTolak.style.display = 'none';
    if (formTolakPengembalian) formTolakPengembalian.style.display = 'none';

    var el = document.getElementById(id);
    if (el) {
        el.style.display = 'block';
        el.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });
    }
}

function tutupForm(id) {
    var el = document.getElementById(id);
    if (el) el.style.display = 'none';
}

function konfirmasiAksi(formId, judul, pesan, ikon, warnaTombol) {

    // Set default warna jika tidak dikirim (Fallback ke Navy)
    if (!warnaTombol) warnaTombol = '#0d1b3e';

    Swal.fire({
        title: judul,
        text: pesan,
        icon: ikon,
        showCancelButton: true,
        confirmButtonColor: warnaTombol, // Warna dinamis (Merah/Navy)
        cancelButtonColor: '#5a5c69',
        confirmButtonText: 'Ya, Proses!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
}
