</div>



<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<script>window.baseUrl = '<?= BASEURL; ?>';</script>
<script src="<?= BASEURL; ?>js/script.js?v=<?= APP_VERSION; ?>"></script>
<script src="<?= BASEURL; ?>js/Beranda.js?v=<?= APP_VERSION; ?>"></script>
<script src="<?= BASEURL; ?>js/Sidebar.js?v=<?= APP_VERSION; ?>"></script>
<script src="<?= BASEURL; ?>js/form.js?v=<?= APP_VERSION; ?>"></script>
<script src="<?= BASEURL; ?>js/upload.js?v=<?= APP_VERSION; ?>"></script>
<script src="<?= BASEURL; ?>js/DetailDetailBarang.js?v=<?= APP_VERSION; ?>"></script>
<script src="<?= BASEURL; ?>js/ValidasiPeminjaman.js?v=<?= APP_VERSION; ?>"></script>

<script src="<?= BASEURL; ?>js/IndexPengembalian.js?v=<?= APP_VERSION; ?>"></script>
<script src="<?= BASEURL; ?>js/DashboardChartsPremium.js?v=<?= APP_VERSION; ?>"></script>

<script>
    // 1. Logika Menghilangkan Loader setelah halaman Load
    $(window).on('load', function () {
        setTimeout(function () {
            $('#loading-screen').addClass('hidden');
        }, 0);
    });

    // 2. Logika Memunculkan Loader saat navigasi fitur diklik
    $(document).ready(function () {
        $('.menu-item').on('click', function (e) {
            const href = $(this).attr('href');
            const isModal = $(this).attr('data-toggle') === 'modal';
            const isLogout = $(this).hasClass('logout-link');

            if (href && href !== '#' && !isModal && !isLogout) {
                $('#loading-screen').removeClass('hidden');
            }
        });

        $('#konfirmasiKeluar .btn-danger').on('click', function () {
            $('#loading-screen').removeClass('hidden');
        });

        $('form').on('submit', function () {
            if (!$(this).hasClass('no-loader')) {
                $('#loading-screen').removeClass('hidden');
            }
        });

        // PERBAIKAN: Jangan trigger loader pada klik pagination
        // Karena pagination akan navigate ke halaman yang sama dengan parameter berbeda
        $(document).on('click', '.page-link', function (e) {
            // Cegah event bubbling yang bisa memicu loader
            e.stopPropagation();
        });
    });
</script>

</body>

</html>