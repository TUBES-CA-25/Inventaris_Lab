<?php
if (!isset($_SESSION['login'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}
?>

<div class="content">
    <div class="">
        
        <div class="beranda-header">
            <h2>Beranda</h2>
        </div>

        <div class="card mb-4" style="border-radius: 15px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
            <div class="card-body">
                <div class="row align-items-center g-2">
                    <div class="col-auto">
                        <h5 class="mb-0">Filter Grafik:</h5>
                    </div>
                    
                    <div class="col-12 col-sm-auto">
                        <select id="filterMode" class="form-select">
                            <option value="harian">Harian</option>
                            <option value="bulanan" selected>Bulanan</option>
                            <option value="tahunan">Tahunan</option>
                        </select>
                    </div>

                    <div class="col-12 col-sm-auto">
                        <select id="filterTahun" class="form-select">
                            <?php 
                            $currentYear = date('Y');
                            for($i = $currentYear; $i >= $currentYear - 4; $i--): ?>
                                <option value="<?= $i; ?>"><?= $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="col-12 col-sm-auto d-none" id="filterBulanWrapper">
                        <select id="filterBulan" class="form-select">
                            <option value="1">Januari</option>
                            <option value="2">Februari</option>
                            <option value="3">Maret</option>
                            <option value="4">April</option>
                            <option value="5">Mei</option>
                            <option value="6">Juni</option>
                            <option value="7">Juli</option>
                            <option value="8">Agustus</option>
                            <option value="9">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">November</option>
                            <option value="12">Desember</option>
                        </select>
                    </div>

                    <div class="col-12 col-sm-auto">
                        <button class="btn btn-primary w-100" onclick="updateCharts()">Tampilkan</button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row g-4 mb-4">
            <div class="col-12 col-md-6 col-lg-6 mb-4">
                <div class="chart-card">
                    <div class="chart-header"><h3>Total Peminjaman</h3></div>
                    <div class="chart-container"><canvas id="chartPeminjaman"></canvas></div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6">
                <div class="chart-card">
                    <div class="chart-header"><h3>Total Pengembalian</h3></div>
                    <div class="chart-container"><canvas id="chartPengembalian"></canvas></div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6">
                <div class="chart-card">
                    <div class="chart-header"><h3>Total Barang Bagus</h3></div>
                    <div class="chart-container"><canvas id="chartBarangBagus"></canvas></div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6">
                <div class="chart-card">
                    <div class="chart-header"><h3>Total Barang Rusak</h3></div>
                    <div class="chart-container"><canvas id="chartBarangRusak"></canvas></div>
                </div>
            </div>
        </div>
    </div>
</div>
