document.addEventListener('DOMContentLoaded', function() {
    // Definisi Elemen
    const btnFilterToggle = document.getElementById('btnFilterToggle');
    const filterSection   = document.getElementById('filterSection');
    const btnReset        = document.getElementById('btnReset');
    const searchInput     = document.getElementById('searchInput');
    const filterStatus    = document.getElementById('filterStatus');
    const startDate       = document.getElementById('startDate');
    const endDate         = document.getElementById('endDate');

    const tableRows       = document.querySelectorAll('.data-row');
    const filterNoData    = document.getElementById('filterNoData');
    const noDataRow       = document.getElementById('noDataRow');

    // 1. Fungsi Toggle Tampilan Filter
    window.toggleFilter = function() { // Dijadikan window function agar onclick di HTML jalan
        if (filterSection.style.display === 'none' || filterSection.style.display === '') {
            filterSection.style.display = 'block';
        } else {
            filterSection.style.display = 'none';
        }
    };

    // 2. Fungsi Utama Filter
    function applyFilters() {
        const searchTerm     = searchInput.value.trim().toLowerCase();
        const selectedStatus = filterStatus.value.toLowerCase().trim();
        const start          = startDate.value; 
        const end            = endDate.value;   

        let visibleCount = 0;

        tableRows.forEach(row => {
            const rowStatus = row.getAttribute('data-status')?.toLowerCase() || '';
            const rowDate   = row.getAttribute('data-date') || '';
            const rowText   = row.innerText.toLowerCase(); // Mengambil semua teks dalam row

            // Logika Pencarian Teks
            const matchSearch = !searchTerm || rowText.includes(searchTerm);

            // Logika Status
            const matchStatus = !selectedStatus || rowStatus === selectedStatus;

            // Logika Rentang Tanggal
            let matchDate = true;
            if (start && end) {
                matchDate = rowDate >= start && rowDate <= end;
            } else if (start) {
                matchDate = rowDate >= start;
            } else if (end) {
                matchDate = rowDate <= end;
            }

            // Gabungkan semua kondisi
            if (matchSearch && matchStatus && matchDate) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Tampilkan pesan jika tidak ada hasil
        if (filterNoData) {
            filterNoData.style.display = (visibleCount === 0 && tableRows.length > 0) ? '' : 'none';
        }
        
        // Sembunyikan "Tidak ada data" awal jika kita sedang memfilter
        if (noDataRow && tableRows.length > 0) {
            noDataRow.style.display = 'none';
        }
    }

    // 3. Event Listeners untuk Otomatisasi
    
    // Input teks (Search)
    searchInput.addEventListener('input', applyFilters);

    // Dropdown Status
    filterStatus.addEventListener('change', applyFilters);

    // Date pickers
    startDate.addEventListener('change', applyFilters);
    endDate.addEventListener('change', applyFilters);

    // Reset Button
    if (btnReset) {
        btnReset.addEventListener('click', function() {
            filterStatus.value = '';
            startDate.value    = '';
            endDate.value      = '';
            searchInput.value  = '';
            applyFilters();
        });
    }

    // Jalankan filter saat halaman dimuat (untuk kondisi default)
    applyFilters();
});