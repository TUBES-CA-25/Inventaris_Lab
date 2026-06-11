(function () {
    let chartLoan, chartItems;

    window.refreshPremiumCharts = function () {
        const modeElem = document.getElementById('filterMode');
        const tahunElem = document.getElementById('filterTahun');
        const bulanElem = document.getElementById('filterBulan');

        if (!modeElem || !tahunElem || !bulanElem) return;

        const mode = modeElem.value;
        const tahun = tahunElem.value;
        const bulan = bulanElem.value;

        const fetchUrl = (window.baseUrl || '') + 'Beranda/getAjaxStats';

        fetch(fetchUrl, {
            method: 'POST',
            body: JSON.stringify({ mode, tahun, bulan }),
            headers: { 'Content-Type': 'application/json' }
        })
            .then(res => res.json())
            .then(data => {
                if (chartLoan) {
                    const maxLoan = Math.max(...data.peminjaman, ...data.pengembalian, 0);
                    chartLoan.data.labels = data.labels;
                    chartLoan.data.datasets[0].data = data.peminjaman;
                    chartLoan.data.datasets[1].data = data.pengembalian;
                    chartLoan.options.scales.y.max = maxLoan + 5;
                    chartLoan.update();
                }

                if (chartItems) {
                    const maxItems = Math.max(...data.total_barang_baru, 0);
                    chartItems.data.labels = data.labels;
                    chartItems.data.datasets[0].data = data.total_barang_baru;
                    chartItems.options.scales.y.max = maxItems + 5;
                    chartItems.update();
                }
            })
            .catch(err => console.error("Error updating charts:", err));
    };

    function initCharts() {
        const ctxLoanElem = document.getElementById('chartCombinedLoan');
        if (ctxLoanElem) {
            const ctxLoan = ctxLoanElem.getContext('2d');
            chartLoan = new Chart(ctxLoan, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [
                        {
                            label: 'Peminjaman',
                            borderColor: '#0c1740',
                            backgroundColor: 'rgba(12, 23, 64, 0.05)',
                            borderWidth: 3,
                            data: [],
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Pengembalian',
                            borderColor: '#3b82f6',
                            backgroundColor: 'transparent',
                            borderWidth: 3,
                            data: [],
                            tension: 0.4,
                            fill: false
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 1000,
                        easing: 'easeInOutQuart'
                    },
                    plugins: {
                        legend: { position: 'top', labels: { usePointStyle: true, font: { weight: '600' } } }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { maxTicksLimit: 20 }
                        },
                        y: {
                            beginAtZero: true,
                            suggestedMax: 10,
                            ticks: { stepSize: 2 },
                            grid: { borderDash: [5, 5] }
                        }
                    }
                }
            });
        }

        const ctxItemsElem = document.getElementById('chartNewItems');
        if (ctxItemsElem) {
            const ctxItems = ctxItemsElem.getContext('2d');
            chartItems = new Chart(ctxItems, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Unit Baru',
                        backgroundColor: '#0c1740',
                        borderRadius: 8,
                        data: []
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 1000,
                        easing: 'easeInOutQuart'
                    },
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { maxTicksLimit: 20 }
                        },
                        y: {
                            beginAtZero: true,
                            suggestedMax: 10,
                            ticks: { stepSize: 2 },
                            grid: { borderDash: [5, 5] }
                        }
                    }
                }
            });
        }
    }

    function initCustomSelects() {
        const dropdowns = document.querySelectorAll('.custom-select-wrapper');

        dropdowns.forEach(wrapper => {
            const trigger = wrapper.querySelector('.custom-select-trigger');
            const options = wrapper.querySelectorAll('.custom-option');
            const hiddenSelect = wrapper.querySelector('select');
            const label = wrapper.querySelector('span');

            if (!trigger || !hiddenSelect || !label) return;

            trigger.addEventListener('click', function (e) {
                e.stopPropagation();
                dropdowns.forEach(dw => {
                    if (dw !== wrapper) dw.classList.remove('open');
                });
                wrapper.classList.toggle('open');
            });

            options.forEach(option => {
                option.addEventListener('click', function () {
                    const value = this.getAttribute('data-value');
                    const text = this.textContent;

                    label.textContent = text;
                    hiddenSelect.value = value;

                    options.forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');

                    wrapper.classList.remove('open');

                    const event = new Event('change', { bubbles: true });
                    hiddenSelect.dispatchEvent(event);

                    if (hiddenSelect.id === 'filterMode') {
                        const bulbWrapper = document.getElementById('filterBulanWrapper');
                        if (bulbWrapper) {
                            if (value === 'harian') bulbWrapper.classList.remove('d-none');
                            else bulbWrapper.classList.add('d-none');
                        }

                        const yearWrapper = document.getElementById('filterTahunWrapper');
                        if (yearWrapper) {
                            if (value === 'tahunan') yearWrapper.classList.add('d-none');
                            else yearWrapper.classList.remove('d-none');
                        }
                    }
                });
            });
        });

        document.addEventListener('click', function () {
            dropdowns.forEach(dw => dw.classList.remove('open'));
        });
    }

    window.addEventListener('load', function () {
        initCustomSelects();
        initCharts();
        if (document.getElementById('chartCombinedLoan') || document.getElementById('chartNewItems')) {
            window.refreshPremiumCharts();
        }
    });
})();
