document.addEventListener('DOMContentLoaded', function() {
    var form = document.querySelector('form');
    if (!form) return;
    form.addEventListener('submit', function(e) {
        var btn = document.getElementById('btnSubmitPeminjaman');
        if (btn) {
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
            btn.style.opacity = '0.7';
            btn.style.pointerEvents = 'none';
        }
    });
});

function tutupModal() {
    var overlay = document.getElementById('modalHapus');
    if (overlay) overlay.classList.remove('show');
}

function konfirmasiHapus(url){
    var overlay = document.getElementById('modalHapus');
    var link = document.getElementById('btnLinkHapus');
    if(overlay && link){
        link.href = url;
        overlay.classList.add('show');
    }
}
