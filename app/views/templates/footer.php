</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
<script src=" https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.j"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->

<script src="<?= BASEURL; ?>/js/script.js"></script>
<script src="<?= BASEURL; ?>/js/form.js"></script>

<script src="<?= BASEURL; ?>/js/upload.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="<?= BASEURL; ?>public/js/ValidasiPeminjaman.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>

<script>
    // 1. Logika Menghilangkan Loader setelah halaman Load
    $(window).on('load', function() {
        // Beri sedikit delay (500ms) agar transisi hilangnya halus
        setTimeout(function() {
            $('#loading-screen').addClass('hidden');
        }, 500);
    });

    // 2. Logika Memunculkan Loader saat navigasi fitur diklik
    $(document).ready(function() {
        // Targetkan semua link di menu-item sidebar
        $('.menu-item').on('click', function(e) {
            
            // Cek: Jangan munculkan loader jika:
            // - Link adalah Logout (karena ada modal konfirmasi dulu)
            // - Link adalah pemicu Modal (seperti tombol Tambah Data)
            // - Link adalah link kosong '#'
            
            const href = $(this).attr('href');
            const isModal = $(this).attr('data-toggle') === 'modal';
            const isLogout = $(this).hasClass('logout-link');

            if (href && href !== '#' && !isModal && !isLogout) {
                $('#loading-screen').removeClass('hidden');
            }
        });

        // 3. Khusus untuk Tombol Logout (Munculkan loader setelah klik 'Keluar' di Modal)
        $('#konfirmasiKeluar .btn-danger').on('click', function() {
            $('#loading-screen').removeClass('hidden');
        });
        
        // 4. Munculkan loader saat form disubmit (Misal: Tambah Barang/Filter)
        $('form').on('submit', function() {
            // Pastikan form tidak memiliki class 'no-loader' jika ada form khusus
            if (!$(this).hasClass('no-loader')) {
                $('#loading-screen').removeClass('hidden');
            }
        });
    });
</script>

</body>

</html>