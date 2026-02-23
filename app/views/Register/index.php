<form action="<?= BASEURL; ?>/Register/tambah" method="post" enctype="multipart/form-data" id="form-register">
  <!-- Hidden input for cropped base64 image -->
  <input type="hidden" name="cropped_foto" id="cropped_foto">
  <div class="body-register row">
    <div class="figure-daftar col-12 col-lg-5">
      <div class="logo">
        <img src="<?= BASEURL ?>img/logo bg putih.svg" alt="logo" />
      </div>
      <div class="content-figure">
        <img id="img-figure-daftar" src="<?= BASEURL ?>img/happy robot assistant.svg" alt="figure" />
      </div>
    </div>
    <div class="form-daftar-kiri col-12 col-lg-3">
      <div class="flash" style="width: 80%;">
        <?php Flasher::flash(); ?>
      </div>
      <div class="header">
        <h2>Daftar</h2>
      </div>
      <div class="form row">
        <div class="nama col-12">
          <label for="nama_user">Nama Lengkap</label>
          <input type="text" id="nama_user" placeholder="Masukkan nama lengkap anda" maxlength="100" name="nama_user"
            required />
        </div>
        <div class="kategori col-12 mb-3">
          <label>Asal Instansi</label>
          <div class="jenis-kelamin">
            <div class="fikom">
              <input type="radio" name="asal_instansi" id="fikom" value="fikom" checked onchange="updateLabel(this)"
                required />
              <span for="fikom">FIKOM</span>
            </div>
            <div class="luar-fikom">
              <input type="radio" name="asal_instansi" id="luar_fikom" value="luar_fikom" onchange="updateLabel(this)"
                required />
              <span for="luar_fikom">Luar FIKOM</span>
            </div>
          </div>
        </div>
        <div class="nim_nip col-12">
          <label for="nim_nip" id="label_nim_nip">NIM / NIDN</label>
          <input type="text" id="nim_nip" name="nim_nip" placeholder="Masukkan NIM atau NIDN anda" maxlength="30"
            required oninput="validasiInput(this)" />
        </div>
        <div class="email col-12">
          <label for="email">Email</label>
          <input type="email" name="email" placeholder="Masukkan email anda" required />
        </div>
        <div class="kata-sandi col-12">
          <label for="password">Kata sandi</label>
          <input type="password" name="password" id="password" minlength="8" placeholder="Masukkan kata sandi anda"
            required />
        </div>
        <div class="konfirmasi-kata-sandi col-12">
          <label for="konfirmasi-password">Konfirmasi kata sandi</label>
          <input type="password" name="konfirmasi-password" id="konfirmasi-password" minlength="8"
            placeholder="Masukkan kata sandi anda" required />
          <div id="error_message" style="color: red;"></div>
        </div>
      </div>
    </div>
    <div class="form-daftar-kanan col-12 col-lg-4">
      <div class="foto-upload-container text-center mb-4">
        <div class="circular-preview-wrapper mx-auto mb-2"
          style="width: 125px; height: 125px; border-radius: 50%; border: 3px dashed #60a5fa; overflow: hidden; display: flex; align-items: center; justify-content: center; background: #f3f4f6; cursor: pointer;"
          onclick="document.getElementById('foto').click()">
          <img id="preview-circular" src="<?= BASEURL ?>img/foto-profile/user.svg"
            style="width: 100%; height: 100%; object-fit: cover;" />
        </div>
        <input type="file" name="foto" id="foto" accept="image/png, image/jpeg, image/jpg"
          onchange="handleFileSelect(event)" style="display: none;" />
        <label for="foto" class="btn btn-outline-primary btn-sm mt-2">Pilih Foto (Maks 2MB)</label>
      </div>

      <!-- Cropper Modal -->
      <div id="cropper-modal"
        class="fixed inset-0 z-[10000] hidden flex items-center justify-center bg-black/80 backdrop-blur-sm p-4">
        <div class="bg-white rounded-2xl w-full max-w-lg overflow-hidden shadow-2xl">
          <div class="p-4 border-b flex justify-between items-center bg-gray-50">
            <h3 class="font-bold text-gray-800">Sesuaikan Foto Profil</h3>
            <button type="button" onclick="closeCropper()" class="text-gray-500 hover:text-red-500 transition-colors">
              <i class="fa-solid fa-xmark text-xl"></i>
            </button>
          </div>
          <div class="p-6">
            <div class="cropper-container" style="max-height: 400px; background: #000;">
              <img id="image-to-crop" style="max-width: 100%; display: block;">
            </div>
            <div class="mt-4 flex gap-3">
              <button type="button" onclick="cropImage()"
                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition-all shadow-lg active:scale-95">
                Simpan & Gunakan
              </button>
            </div>
          </div>
        </div>
      </div>
      <label>Jenis Kelamin</label>
      <div class="jenis-kelamin">
        <div class="laki-laki">
          <input type="radio" name="jenis_kelamin" id="laki-laki" value="laki-laki" required />
          <label for="laki-laki">laki-laki</label>
        </div>
        <div class="perempuan">
          <input type="radio" name="jenis_kelamin" id="perempuan" value="perempuan" required />
          <label for="perempuan">perempuan</label>
        </div>
      </div>
      <div class="no_hp">
        <label for="no_hp_user">No. Hp</label>
        <input type="text" name="no_hp_user" id="no_hp_user" placeholder="Masukkan no. Hp anda" required maxlength="13"
          oninput="validasiInput(this)" />
      </div>
      <div class="alamat">
        <label for="alamat">Alamat</label>
        <input type="text" name="alamat" placeholder="Masukkan alamat anda" required maxlength="100" />
      </div>
      <div class="action-buttons">
        <a href="<?= BASEURL; ?>Login" class="btn-kembali">Kembali</a>

        <button type="submit" id="btn-daftar">Daftar</button>
      </div>
    </div>
  </div>
</form>

<script>
  let cropper;
  const imageToCrop = document.getElementById('image-to-crop');
  const cropperModal = document.getElementById('cropper-modal');
  const previewCircular = document.getElementById('preview-circular');
  const croppedInput = document.getElementById('cropped_foto');
  const form = document.getElementById('form-register');

  function updateLabel(radio) {
    const label = document.getElementById('label_nim_nip');
    const input = document.getElementById('nim_nip');
    if (radio.value === 'fikom') {
      label.innerText = 'NIM / NIDN';
      input.placeholder = 'Masukkan NIM atau NIDN anda';
    } else {
      label.innerText = 'NIK';
      input.placeholder = 'Masukkan NIK anda';
    }
  }

  function handleFileSelect(event) {
    const file = event.target.files[0];
    if (file) {
      if (file.size > 2 * 1024 * 1024) {
        Swal.fire('Error', 'Ukuran foto maksimal 2MB', 'error');
        event.target.value = '';
        return;
      }

      const reader = new FileReader();
      reader.onload = function (e) {
        imageToCrop.src = e.target.result;
        cropperModal.classList.remove('hidden');

        if (cropper) cropper.destroy();

        cropper = new Cropper(imageToCrop, {
          aspectRatio: 1,
          viewMode: 1,
          dragMode: 'move',
          autoCropArea: 1,
          restore: false,
          guides: false,
          center: false,
          highlight: false,
          cropBoxMovable: false,
          cropBoxResizable: false,
          toggleDragModeOnDblclick: false,
          ready() {
            // Force circular mask visually
            const cropperDragBox = document.querySelector('.cropper-drag-box');
            const cropperViewBox = document.querySelector('.cropper-view-box');
            const cropperFace = document.querySelector('.cropper-face');

            if (cropperViewBox) cropperViewBox.style.borderRadius = '50%';
            if (cropperFace) cropperFace.style.borderRadius = '50%';
          }
        });
      }
      reader.readAsDataURL(file);
    }
  }

  function closeCropper() {
    cropperModal.classList.add('hidden');
    document.getElementById('foto').value = '';
  }

  function cropImage() {
    const canvas = cropper.getCroppedCanvas({
      width: 400,
      height: 400,
      imageSmoothingEnabled: true,
      imageSmoothingQuality: 'high',
    });

    // Create a circular crop on the canvas itself
    const circularCanvas = document.createElement('canvas');
    circularCanvas.width = 400;
    circularCanvas.height = 400;
    const ctx = circularCanvas.getContext('2d');

    ctx.beginPath();
    ctx.arc(200, 200, 200, 0, Math.PI * 2);
    ctx.clip();
    ctx.drawImage(canvas, 0, 0, 400, 400);

    const base64 = circularCanvas.toDataURL('image/png');
    previewCircular.src = base64;
    croppedInput.value = base64;
    cropperModal.classList.add('hidden');
  }

  form.addEventListener('submit', function (e) {
    const asal = document.querySelector('input[name="asal_instansi"]:checked').value;
    const nim_nip = document.getElementById('nim_nip').value;
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('konfirmasi-password').value;

    // 1. Password validation
    if (password !== confirmPassword) {
      e.preventDefault();
      $('#loading-screen').addClass('hidden');
      Swal.fire('Error', 'Konfirmasi kata sandi tidak cocok', 'error');
      return;
    }

    // 2. Strict NIM/NIDN/NIK validation
    if (asal === 'fikom') {
      const isMHS = (nim_nip.startsWith('130') || nim_nip.startsWith('131')) && nim_nip.length === 11;
      const isDosen = nim_nip.startsWith('09') && nim_nip.length === 10;

      if (!isMHS && !isDosen) {
        e.preventDefault();
        $('#loading-screen').addClass('hidden');
        Swal.fire('Format Tidak Valid', 'FIKOM hanya boleh NIM (Prefix 130/131, 11 digit) atau NIDN (Prefix 09, 10 digit).', 'warning');
        return;
      }
    } else {
      // Luar FIKOM - cannot use FIKOM patterns
      const isFIKOMPattern = ((nim_nip.startsWith('130') || nim_nip.startsWith('131')) && nim_nip.length === 11) || (nim_nip.startsWith('09') && nim_nip.length === 10);

      if (isFIKOMPattern) {
        e.preventDefault();
        $('#loading-screen').addClass('hidden');
        Swal.fire('Format Salah', 'Data FIKOM dideteksi. Silakan pilih "Asal Instansi: FIKOM" atau masukkan NIK yang benar.', 'warning');
        return;
      }
    }
  });

  // Numeric only for NIM/NIDN/NIK
  document.getElementById('nim_nip').onkeypress = function (e) {
    if (isNaN(String.fromCharCode(e.which))) e.preventDefault();
  };
</script>

<style>
  /* Fix cropper circular view */
  .cropper-view-box,
  .cropper-face {
    border-radius: 50%;
  }
</style>