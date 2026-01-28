function bukaFormTolak(id) {
    // Tutup semua form dulu
    document.getElementById('formTolakContainer').style.display = 'none';
    document.getElementById('formTolakPengembalianContainer').style.display = 'none';

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