document.addEventListener('DOMContentLoaded', function(){
    // modal populate handler (requires modal elements to exist)
    $(document).ready(function() {
        $('.btnPinjam').on('click', function() {
            const idBarang = $(this).data('id');
            const namaBarang = $(this).data('nama');
            $('#modal_id_barang').val(idBarang);
            $('#modal_nama_barang').val(namaBarang);
        });
    });
});
