document.addEventListener("DOMContentLoaded", function () {

    // Ambil nilai dari variabel PHP $isEdit yang kita buat di baris atas tadi
    const isEditMode = <?= json_encode($isEdit); ?>;

    console.log("Status Edit Mode:", isEditMode); // Cek console browser Anda

    if (isEditMode === true) {

        // Gunakan Event Delegation pada BODY agar menangkap klik di Sidebar juga
        document.body.addEventListener('click', function (e) {

            // Cari elemen <a> (link) terdekat dari yang diklik
            const link = e.target.closest('a');

            // Jika bukan link, abaikan
            if (!link) return;

            const targetUrl = link.getAttribute('href');

            // A. WHITELIST: Abaikan link kosong/hash/javascript
            if (!targetUrl || targetUrl === '#' || targetUrl.startsWith('javascript')) return;

            // B. WHITELIST: Tombol "Pinjam" (PENTING: Biarkan user menambah barang)
            // Kita cek class 'btn-pinjam-now' yang ada di tombol pinjam
            if (link.classList.contains('btn-pinjam-now')) return;

            // C. WHITELIST: Tombol Logout / Modal (PENTING: Biarkan modal muncul)
            if (link.hasAttribute('data-toggle') || link.hasAttribute('data-target')) return;

            // D. WHITELIST: Link ke Form Peminjaman sendiri
            if (targetUrl.includes('Peminjaman/formPeminjaman')) return;

            // --- BLOKIR NAVIGASI & TAMPILKAN POPUP ---
            e.preventDefault();
            e.stopImmediatePropagation(); // Hentikan script lain (seperti loader)

            Swal.fire({
                title: 'Batal Memilih Barang?',
                text: "Anda sedang dalam mode Edit/Tambah barang. Keluar sekarang akan membatalkan proses pemilihan.",
                icon: 'warning',
                showCancelButton: true,
                showDenyButton: true,
                confirmButtonColor: '#d33',     // Merah (Keluar)
                denyButtonColor: '#0d1b3e',     // Navy (Kembali ke Form)
                cancelButtonColor: '#6e7881',   // Abu (Tetap Disini)
                confirmButtonText: 'Keluar & Batal Edit',
                denyButtonText: 'Kembali ke Form',
                cancelButtonText: 'Tetap Disini'
            }).then((result) => {
                if (result.isConfirmed) {
                    // OPSI 1: KELUAR & HAPUS SESI EDIT
                    window.location.href = '<?= BASEURL; ?>Peminjaman/batalEdit';

                } else if (result.isDenied) {
                    // OPSI 2: BALIK KE FORM (Tanpa nambah barang)
                    window.location.href = '<?= BASEURL; ?>Peminjaman/formPeminjaman';

                } else {
                    // OPSI 3: DIAM (Tutup popup)
                }
            });
        }, true); // 'true' = Capture Phase (Prioritas Tinggi)
    }
});