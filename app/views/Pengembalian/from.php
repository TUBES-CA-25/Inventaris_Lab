<?php
if (!isset($_SESSION['login'])) {
    header("Location:" . BASEURL . "Login");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul_kegiatan = $_POST['judul_kegiatan'] ?? '';
    $jenis_barang = $_POST['jenis_barang'] ?? '';
    $mulai_tanggal = $_POST['mulai_tanggal'] ?? '';
    $sampai_tanggal = $_POST['sampai_tanggal'] ?? '';
    $jumlah = $_POST['jumlah'] ?? '';
    $keterangan = $_POST['keterangan'] ?? '';
    
    $upload_dir = '../public/uploads/pengembalian/';
    $file_name = '';
    
    if (isset($_FILES['bukti_pengembalian']) && $_FILES['bukti_pengembalian']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'pdf'];
        $filename = $_FILES['bukti_pengembalian']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_name = uniqid() . '_' . $filename;
            move_uploaded_file($_FILES['bukti_pengembalian']['tmp_name'], $upload_dir . $file_name);
        }
    }
    
    // Validasi
    if (!empty($judul_kegiatan) && !empty($jenis_barang) && !empty($mulai_tanggal) && !empty($sampai_tanggal) && !empty($jumlah)) {
        Flasher::setFlash('Data pengembalian berhasil ditambahkan!', 'success');
        header('Location: ' . BASEURL . 'Pengembalian');
        exit;
    } else {
        Flasher::setFlash('Mohon lengkapi semua field yang diperlukan!', 'danger');
    }
}
?>

<div class="content">
    <div class="content-beranda" style="overflow: hidden;">
        <h3 id="title">Pengembalian</h3>
        <div class="flash" style="width: 40%; margin-left:15px;">
            <?php Flasher::flash(); ?>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; padding: 20px;">
            <!-- Form Section -->
            <div style="display: flex; flex-direction: column; gap: 25px;">
                <form method="POST" action="<?= BASEURL; ?>Pengembalian/tambah" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="judul_kegiatan" style="font-weight: 600; color: #1e293b; margin-bottom: 10px;">Judul kegiatan</label>
                        <input type="text" class="form-control" id="judul_kegiatan" name="judul_kegiatan" 
                            style="padding: 14px 18px; border: 2px solid #e2e8f0; border-radius: 12px; background: #f8fafc;" required>
                    </div>

                    <div class="form-group">
                        <label for="jenis_barang" style="font-weight: 600; color: #1e293b; margin-bottom: 10px;">Jenis barang</label>
                        <select class="form-control" id="jenis_barang" name="jenis_barang" 
                            style="padding: 14px 18px; border: 2px solid #e2e8f0; border-radius: 12px; background: #f8fafc;" required>
                            <option value="">Select Option</option>
                            <option value="elektronik">Elektronik</option>
                            <option value="alat-tulis">Alat Tulis</option>
                            <option value="furniture">Furniture</option>
                            <option value="kendaraan">Kendaraan</option>
                        </select>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label for="mulai_tanggal" style="font-weight: 600; color: #1e293b; margin-bottom: 10px;">Mulai dari tanggal</label>
                            <input type="date" class="form-control" id="mulai_tanggal" name="mulai_tanggal" 
                                style="padding: 14px 18px; border: 2px solid #e2e8f0; border-radius: 12px; background: #f8fafc;" required>
                        </div>

                        <div class="form-group">
                            <label for="sampai_tanggal" style="font-weight: 600; color: #1e293b; margin-bottom: 10px;">Sampai tanggal</label>
                            <input type="date" class="form-control" id="sampai_tanggal" name="sampai_tanggal" 
                                style="padding: 14px 18px; border: 2px solid #e2e8f0; border-radius: 12px; background: #f8fafc;" required>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label for="jumlah" style="font-weight: 600; color: #1e293b; margin-bottom: 10px;">Jumlah</label>
                            <input type="number" class="form-control" id="jumlah" name="jumlah" min="1" 
                                style="padding: 14px 18px; border: 2px solid #e2e8f0; border-radius: 12px; background: #f8fafc;" required>
                        </div>

                        <div class="form-group">
                            <label for="keterangan" style="font-weight: 600; color: #1e293b; margin-bottom: 10px;">Keterangan</label>
                            <input type="text" class="form-control" id="keterangan" name="keterangan" 
                                style="padding: 14px 18px; border: 2px solid #e2e8f0; border-radius: 12px; background: #f8fafc;">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="bukti_pengembalian" style="font-weight: 600; color: #1e293b; margin-bottom: 10px;">Upload Bukti Pengembalian</label>
                        <input type="file" class="form-control-file" id="bukti_pengembalian" name="bukti_pengembalian" 
                            accept="image/*,.pdf" 
                            style="padding: 14px 18px; border: 2px dashed #cbd5e1; border-radius: 12px; background: #f8fafc; width: 100%;">
                    </div>

                    <div style="display: flex; width:100%; justify-content: end; align-items: end; margin-top: 20px;">
                        <button type="submit" id="kirim" class="btn" 
                            style="background: #0d1a4a; color: white; padding: 16px 40px; border-radius: 12px; font-weight: 600;" 
                            onclick="return confirm('Yakin ingin mengirim data?');">Kirim</button>
                    </div>
                </form>
            </div>

            <!-- Robot Section -->
            <div class="content-figure" style="position: relative; display: flex; justify-content: center; align-items: center;">
                <img id="img-figure-daftar" 
                    src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 350'%3E%3Cellipse cx='150' cy='220' rx='85' ry='95' fill='%23e5e7eb'/%3E%3Ccircle cx='150' cy='245' r='12' fill='white' stroke='%2394a3b8' stroke-width='2'/%3E%3Cpath d='M 150 245 m -6 0 a 1 1 0 0 0 12 0' fill='none' stroke='%2394a3b8' stroke-width='2'/%3E%3Crect x='95' y='80' width='110' height='90' rx='20' fill='%231e293b'/%3E%3Crect x='110' y='105' width='35' height='28' rx='8' fill='%2306b6d4'/%3E%3Crect x='155' y='105' width='35' height='28' rx='8' fill='%2306b6d4'/%3E%3Cline x1='117' y1='112' x2='138' y2='112' stroke='%230891b2' stroke-width='3'/%3E%3Cline x1='117' y1='120' x2='138' y2='120' stroke='%230891b2' stroke-width='3'/%3E%3Cline x1='162' y1='112' x2='183' y2='112' stroke='%230891b2' stroke-width='3'/%3E%3Cline x1='162' y1='120' x2='183' y2='120' stroke='%230891b2' stroke-width='3'/%3E%3Cline x1='150' y1='80' x2='150' y2='50' stroke='%2364748b' stroke-width='4'/%3E%3Ccircle cx='150' cy='42' r='8' fill='%23ef4444'/%3E%3Crect x='50' y='170' width='40' height='18' rx='9' fill='%23cbd5e1'/%3E%3Crect x='210' y='170' width='40' height='18' rx='9' fill='%23cbd5e1'/%3E%3Ccircle cx='115' cy='305' r='20' fill='%23475569'/%3E%3Ccircle cx='185' cy='305' r='20' fill='%23475569'/%3E%3Ccircle cx='115' cy='305' r='10' fill='%2394a3b8'/%3E%3Ccircle cx='185' cy='305' r='10' fill='%2394a3b8'/%3E%3C/svg%3E" 
                    alt="Robot" style="max-width: 100%; height: auto;">
                <div class="hello-text">Hello!</div>
            </div>
        </div>
    </div>
</div>