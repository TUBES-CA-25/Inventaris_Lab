<?php
if (!isset($_SESSION['login'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}
?>

<style>
    .beranda-container {
        padding: 30px;
        background: #f5f7fa;
        min-height: 100vh;
    }

    .beranda-header {
        background: #0C1740;
        color: white;
        padding: 25px 40px;
        border-radius: 15px;
        margin-bottom: 40px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .beranda-header h2 {
        font-size: 28px;
        font-weight: 700;
        margin: 0;
    }

    .charts-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 30px;
        margin-bottom: 30px;
    }

    .chart-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .chart-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }

    .chart-header {
        background: #0C1740;
        color: white;
        padding: 15px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        text-align: center;
    }

    .chart-header h3 {
        font-size: 18px;
        font-weight: 600;
        margin: 0;
    }

    .chart-container {
        background: white;
        padding: 20px;
        border-radius: 10px;
        height: 300px;
    }

    .flash-message-container {
        margin-bottom: 20px;
    }

    @media (max-width: 1024px) {
        .charts-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .beranda-container {
            padding: 15px;
        }

        .beranda-header {
            padding: 20px;
        }

        .beranda-header h2 {
            font-size: 22px;
        }

        .chart-card {
            padding: 20px;
        }
    }
</style>

<!-- Modal keluar -->
<div class="modal fade" id="konfirmasiKeluar" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-keluar">
            <div class="modal-body modal-body-keluar">
                <lottie-player 
                    src="https://lottie.host/48c004f8-57cd-4acb-a04a-de46793ba7dc/jUGVFL9qIO.json"
                    background="##FFFFFF" 
                    speed="1" 
                    class="lottie-animation" 
                    loop 
                    autoplay 
                    direction="1"
                    mode="normal">
                </lottie-player>
                <p class="modal-confirmation-text">Apakah anda yakin ingin keluar?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-modal-cancel" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger btn-modal-logout" onclick="location.href='<?= BASEURL; ?>Logout'">Keluar</button>
            </div>
        </div>
    </div>
</div>

<!-- Content Area -->
<div class="content">
    <div class="beranda-container">
        
        <!-- Flash Message -->
        <div class="flash flash-message-container">
            <?php Flasher::flash(); ?>
        </div>
        
        <!-- Header -->
        <div class="beranda-header">
            <h2>Beranda</h2>
        </div>
        
        <!-- Charts Grid -->
        <div class="charts-grid">
            
            <!-- Chart 1: Total Peminjaman -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3>Total Peminjaman</h3>
                </div>
                <div class="chart-container">
                    <canvas id="chartPeminjaman"></canvas>
                </div>
            </div>
            
            <!-- Chart 2: Total Pengembalian -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3>Total Pengembalian</h3>
                </div>
                <div class="chart-container">
                    <canvas id="chartPengembalian"></canvas>
                </div>
            </div>
            
            <!-- Chart 3: Total Barang Bagus -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3>Total Barang Bagus</h3>
                </div>
                <div class="chart-container">
                    <canvas id="chartBarangBagus"></canvas>
                </div>
            </div>
            
            <!-- Chart 4: Total Barang Rusak -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3>Total Barang Rusak</h3>
                </div>
                <div class="chart-container">
                    <canvas id="chartBarangRusak"></canvas>
                </div>
            </div>
            
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Konfigurasi warna untuk grafik
    const colors = {
        purple: 'rgb(147, 112, 219)',
        orange: 'rgb(255, 159, 64)',
        cyan: 'rgb(75, 192, 192)',
        purpleTransparent: 'rgba(147, 112, 219, 0.2)',
        orangeTransparent: 'rgba(255, 159, 64, 0.2)',
        cyanTransparent: 'rgba(75, 192, 192, 0.2)'
    };

    // Data bulan
    const months = ['jan', 'feb', 'mar', 'apr', 'mei', 'jun', 'jul', 'aug', 'sept', 'oct', 'nov', 'dec'];

    // Fungsi untuk generate data random (simulasi)
    function generateRandomData(min, max, count) {
        return Array.from({length: count}, () => Math.floor(Math.random() * (max - min + 1)) + min);
    }

    // Konfigurasi umum untuk semua chart
    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'bottom',
                labels: {
                    padding: 15,
                    font: {
                        size: 11
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                max: 100,
                ticks: {
                    stepSize: 20
                }
            },
            x: {
                ticks: {
                    font: {
                        size: 10
                    }
                }
            }
        }
    };

    // Chart 1: Total Peminjaman
    const ctxPeminjaman = document.getElementById('chartPeminjaman').getContext('2d');
    new Chart(ctxPeminjaman, {
        type: 'line',
        data: {
            labels: months,
            datasets: [
                {
                    label: '2025',
                    data: generateRandomData(20, 80, 12),
                    borderColor: colors.purple,
                    backgroundColor: colors.purpleTransparent,
                    tension: 0.4,
                    fill: true
                },
                {
                    label: '2024',
                    data: generateRandomData(30, 70, 12),
                    borderColor: colors.orange,
                    backgroundColor: colors.orangeTransparent,
                    tension: 0.4,
                    fill: true
                },
                {
                    label: '2023',
                    data: generateRandomData(25, 65, 12),
                    borderColor: colors.cyan,
                    backgroundColor: colors.cyanTransparent,
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: commonOptions
    });

    // Chart 2: Total Pengembalian
    const ctxPengembalian = document.getElementById('chartPengembalian').getContext('2d');
    new Chart(ctxPengembalian, {
        type: 'line',
        data: {
            labels: months,
            datasets: [
                {
                    label: '2025',
                    data: generateRandomData(15, 90, 12),
                    borderColor: colors.purple,
                    backgroundColor: colors.purpleTransparent,
                    tension: 0.4,
                    fill: true
                },
                {
                    label: '2024',
                    data: generateRandomData(20, 75, 12),
                    borderColor: colors.orange,
                    backgroundColor: colors.orangeTransparent,
                    tension: 0.4,
                    fill: true
                },
                {
                    label: '2023',
                    data: generateRandomData(10, 70, 12),
                    borderColor: colors.cyan,
                    backgroundColor: colors.cyanTransparent,
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: commonOptions
    });

    // Chart 3: Total Barang Bagus
    const ctxBarangBagus = document.getElementById('chartBarangBagus').getContext('2d');
    new Chart(ctxBarangBagus, {
        type: 'line',
        data: {
            labels: months,
            datasets: [
                {
                    label: '2025',
                    data: generateRandomData(60, 100, 12),
                    borderColor: colors.purple,
                    backgroundColor: colors.purpleTransparent,
                    tension: 0.4,
                    fill: true
                },
                {
                    label: '2024',
                    data: generateRandomData(40, 90, 12),
                    borderColor: colors.orange,
                    backgroundColor: colors.orangeTransparent,
                    tension: 0.4,
                    fill: true
                },
                {
                    label: '2023',
                    data: generateRandomData(10, 60, 12),
                    borderColor: colors.cyan,
                    backgroundColor: colors.cyanTransparent,
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: commonOptions
    });

    // Chart 4: Total Barang Rusak
    const ctxBarangRusak = document.getElementById('chartBarangRusak').getContext('2d');
    new Chart(ctxBarangRusak, {
        type: 'line',
        data: {
            labels: months,
            datasets: [
                {
                    label: '2025',
                    data: generateRandomData(10, 50, 12),
                    borderColor: colors.purple,
                    backgroundColor: colors.purpleTransparent,
                    tension: 0.4,
                    fill: true
                },
                {
                    label: '2024',
                    data: generateRandomData(20, 70, 12),
                    borderColor: colors.orange,
                    backgroundColor: colors.orangeTransparent,
                    tension: 0.4,
                    fill: true
                },
                {
                    label: '2023',
                    data: generateRandomData(40, 70, 12),
                    borderColor: colors.cyan,
                    backgroundColor: colors.cyanTransparent,
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: commonOptions
    });
</script>