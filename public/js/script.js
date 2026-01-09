function uppercaseInput() {
  let inputElement = $("#kode_sub");
  inputElement.value = inputElement.value.toUpperCase();
}


function camelCase() {
  const inputDeskBarang = $("#deskripsi_barang");
  inputDeskBarang.value =
    inputDeskBarang.value.charAt(0).toUpperCase() +
    inputDeskBarang.value.slice(1);

  const inputDetailLokasi = $("#deskripsi_detail_lokasi");
  inputDetailLokasi.value =
    inputDetailLokasi.value.charAt(0).toUpperCase() +
    inputDetailLokasi.value.slice(1);

  const inputSubBarang = $("#sub_barang");
  inputSubBarang.value =
    inputSubBarang.value.charAt(0).toUpperCase() +
    inputSubBarang.value.slice(1);
}

function validasiInput(input) {
  input.value = input.value.replace(/[^0-9]/g, "");
}


// function cetak() {
//   window.print();
$(function () {

  $('.tombolTambahData').on('click', function () {
    $('#tambahPeminjaman').html('Tambah Data Peminjaman');
    $('.modal-footer button[type=submit]').html('Kirim');

  });

  $('.tampilModalPeminjaman').on('click', function () {
    $('#tambahPeminjaman').html('Ubah Data Peminjaman');
    $('.modal-footer button[type=submit]').html('Simpan Perubahan');
    $('.modal-body form').attr('action', 'http://localhost/Inventaris_Lab/public/Peminjaman/ubahPeminjaman');

    const id_peminjaman = $(this).data('id');
    console.log(id_peminjaman);
    if (!id_peminjaman) {
      alert('ID peminjaman tidak ditemukan');
      return;
    }

    $.ajax({
      url: "http://localhost/Inventaris_Lab/public/Peminjaman/getUbah",
      data: { id_peminjaman: id_peminjaman },
      method: 'post',
      dataType: 'json',
      success: function (data) {
        if (data.error) {
          alert(data.error);
          return;
        }

        $('#judul_kegiatan').val(data.judul_kegiatan);
        $('#tanggal_peminjaman').val(data.tanggal_peminjaman);
        $('#tanggal_pengembalian').val(data.tanggal_pengembalian);
        $('#id_jenis_barang').val(data.id_jenis_barang);
        $('#jumlah_peminjaman').val(data.jumlah_peminjaman);
        $('#keterangan_peminjaman').val(data.keterangan_peminjaman);
        $('#id_peminjaman').val(data.id_peminjaman);
      },
      error: function () {
        alert('Terjadi kesalahan saat mengambil data');
      }
    });
  });
});

//Modal Edit Pengembalian

$(document).ready(function () {
  $(document).on('click', '.tampilModalPengembalian', function () {
    $('#modalEditPengembalianLabel').html('Ubah Data Pengembalian');
    $('.modal-footer button[type=submit]').html('Simpan Perubahan');
    $('.modal-body form').attr('action', 'http://localhost/Inventaris_Lab/public/Pengembalian/ubahPengembalian');

    const id_pengembalian = $(this).data('id');
    if (!id_pengembalian) {
      alert('ID pengembalian tidak ditemukan');
      return;
    }

    $.ajax({
      url: "http://localhost/Inventaris_Lab/public/Pengembalian/getUbah",
      data: { id_pengembalian: id_pengembalian },
      method: 'POST',
      dataType: 'json',
      success: function (data) {
        $('#status_pengembalian').val(data.status_pengembalian);
        $('#detail_masalah').val(data.detail_masalah);
        $('#id_pengembalian').val(data.id_pengembalian);
        $('#nama_peminjam').val(data.nama_peminjam);
        $('#tanggal_peminjaman').val(data.tanggal_peminjaman);
        $('#tanggal_pengembalian').val(data.tanggal_pengembalian);

        updateKeterangan();
      },
      error: function (xhr, status, error) {
        console.error("AJAX Error:", status, error);
        alert("Terjadi kesalahan saat mengambil data pengembalian.");
      }
    });
  });

  $('#status_pengembalian').on('change', function () {
    updateKeterangan();
  });

  function updateKeterangan() {
    const status = $('#status_pengembalian').val();
    const tanggalPengembalian = new Date($('#tanggal_pengembalian').val());
    const today = new Date();
    let keterangan = '';

    if (status === 'Dikembalikan') {
      if (today <= tanggalPengembalian) {
        keterangan = 'Tepat Waktu';
      } else {
        keterangan = 'Tidak Tepat Waktu';
      }
    } else if (status === 'Hilang' || status === 'Rusak') {
      keterangan = 'Bermasalah';
      $('#detail_masalah').prop('required', true);
    } else {
      if (today > tanggalPengembalian) {
        keterangan = 'Tidak Tepat Waktu';
      }
    }

    $('#keterangan').val(keterangan);
  }
});


$(document).ready(function () {
  $('.tombolTambahData').on('click', function () {
    $('#tambahPeminjaman').html('Tambah Data Peminjaman');
    $('.modal-footer button[type=submit]').html('Kirim');
  });

  $(document).on('click', '.tampilModalPeminjaman', function () {
    $('#tambahPeminjaman').html('Ubah Data Peminjaman');
    $('.modal-footer button[type=submit]').html('Simpan Perubahan');
    $('.modal-body form').attr('action', 'http://localhost/Inventaris_Lab/public/Peminjaman/ubahPeminjaman');

    const id_peminjaman = $(this).data('id');

    // Debugging untuk memastikan ID terbaca
    console.log("ID Peminjaman:", id_peminjaman);

    if (!id_peminjaman) {
      alert('ID peminjaman tidak ditemukan');
      return;
    }

    $.ajax({
      url: "http://localhost/Inventaris_Lab/public/Peminjaman/getUbah",
      data: { id_peminjaman: id_peminjaman },
      method: 'POST',
      dataType: 'json',
      success: function (data) {
        console.log("Data dari server:", data); // Debugging

        // Mengisi data ke dalam form modal
        $('#judul_kegiatan').val(data.judul_kegiatan);
        $('#nama_peminjam').val(data.nama_peminjam);
        $('#tanggal_peminjaman').val(data.tanggal_peminjaman);
        $('#tanggal_pengembalian').val(data.tanggal_pengembalian);
        $('#id_jenis_barang').val(data.id_jenis_barang).trigger('change');
        $('#jumlah_peminjaman').val(data.jumlah_peminjaman);
        $('#keterangan_peminjaman').val(data.keterangan_peminjaman);
        $('#status').val(data.status);
        $('#id_peminjaman').val(data.id_peminjaman);
      },
      error: function (xhr, status, error) {
        console.error("AJAX Error:", status, error);
        alert("Terjadi kesalahan saat mengambil data peminjaman.");
      }
    });
  });
});


function submitForm() {
  const checkboxes = document.querySelectorAll(".checkbox"); // Mengambil semua checkbox dengan kelas 'checkbox'
  let idbarang = [];

  checkboxes.forEach(function (checkbox) {
    if (checkbox.checked) {
      idbarang.push(checkbox.value); // Menambahkan nilai ID barang yang tercentang
    }
  });
  console.log(idbarang);
  if (idbarang.length === 0) {
    alert("Pilih setidaknya satu barang untuk diekspor!");
    return;
  }

  // Mengisi nilai input hidden dengan ID barang yang dipilih
  document.getElementById("idbarang").value = idbarang.join(","); // Gabungkan ID barang dengan koma

  // Mengirim form setelah mengisi input hidden
  document.getElementById("formCheckbox").submit();
}

//   function tampilCetak() {
//     const checkboxes = document.querySelectorAll(".checkbox");
//     let idbarang = [];

//     checkboxes.forEach(function (checkbox) {
//         if (checkbox.checked) {
//             idbarang.push(checkbox.value);
//         }
//     });

//     if (idbarang.length === 0) {
//         alert("Pilih setidaknya satu barang untuk diekspor!");
//         return;
//     }

//     console.log(idbarang);

//     // Mengisi nilai idbarang ke input hidden
//     document.getElementById("idbarang").value = idbarang.join(",");

//     // Submit form
//     document.getElementById("formCheckbox").submit();
// }


function checkbox() {
  let form = document.getElementById("formCheckbox");

  form.submit();
}

//jenis barang
$(function () {
  $(".btn-tambah").on("click", function () {
    $(".modal-title").html("Tambah Jenis Barang");
    $(".modal-body form").attr(
      "action",
      "http://localhost/Inventaris_Lab/public/JenisBarang/tambahJenisBarang"
    );
    const data = "";
    $("#sub_barang").val(data.sub_barang);
    $("#grup_sub").val(data.grup_sub);
    $("#kode_sub").val(data.kode_sub);
    $("#id_jenis_barang").val(data.id_jenis_barang);
  });

  $(".tampilJenisBarangUbah").on("click", function () {
    $(".modal-title").html("Ubah Jenis Barang");
    $(".modal-body form").attr(
      "action",
      "http://localhost/Inventaris_Lab/public/JenisBarang/ubahJenisBarang"
    );
    const id = $(this).data("id");

    $.ajax({
      url: "http://localhost/Inventaris_Lab/public/JenisBarang/getUbah",
      data: {
        id_jenis_barang: id,
      },
      method: "post",
      dataType: "json",
      success: function (data) {
        $("#sub_barang").val(data.sub_barang);
        $("#grup_sub").val(data.grup_sub);
        $("#kode_sub").val(data.kode_sub);
        $("#id_jenis_barang").val(data.id_jenis_barang);
      },
    });
  });
});

//merek barang
$(function () {
  $(".btn-tambah-merek").on("click", function () {
    $(".title-merek").html("Tambah Merek Barang");
    $(".body-merek form").attr(
      "action",
      "http://localhost/Inventaris_Lab/public/MerekBarang/tambahMerekBarang"
    );
    const data = "";
    $("#nama_merek_barang").val(data.nama_merek_barang);
    $("#kode_merek_barang").val(data.kode_merek_barang);
    $("#id_merek_barang").val(data.id_merek_barang);
  });

  $(".tampilMerekBarangUbah").on("click", function () {
    $(".title-merek").html("Ubah Merek Barang");
    $(".body-merek form").attr(
      "action",
      "http://localhost/Inventaris_Lab/public/MerekBarang/ubahMerekBarang"
    );
    const id = $(this).data("id");

    $.ajax({
      url: "http://localhost/Inventaris_Lab/public/MerekBarang/getUbah",
      data: {
        id_merek_barang: id,
      },
      method: "post",
      dataType: "json",
      success: function (data) {
        $("#nama_merek_barang").val(data.nama_merek_barang);
        $("#kode_merek_barang").val(data.kode_merek_barang);
        $("#id_merek_barang").val(data.id_merek_barang);
      },
    });
  });


  $(document).ready(function () {
    $('#myTable').DataTable();

    $("form#formCheckbox").submit(function (e) {
      const checkboxes = document.querySelectorAll(".checkbox");
      let idbarang = [];

      checkboxes.forEach(function (checkbox) {
        if (checkbox.checked) {
          idbarang.push(checkbox.value);
        }
      });

      if (idbarang.length > 0) {
        $("#idbarang").val(JSON.stringify(idbarang));
      } else {
        $("#idbarang").val("");
      }
    });
  });

  let myTable = $('#myTable').DataTable({
    dom: 'lrtip', // Menghilangkan search bawaan DataTable
    "bLengthChange": false, // Menonaktifkan opsi show entries
    "bInfo": true // Menonaktifkan informasi total entries
  });

  $('select[name="entries_length"]').on('change', function () {
    myTable.page.len($(this).val()).draw(); // Atur jumlah entri per halaman
  });

  $('#customSearch').on('keyup', function () {
    myTable.search(this.value).draw(); // Cari data sesuai input
  });

  let table = $("#example").DataTable({
    lengthChange: false,
    searching: true,
    responsive: true,
    scrollY: 350,
    scrollX: 400,
    pageLength: 20,
    deferRender: true,
    scroller: true,
    dom: "Bfrtip",
    buttons: [
      {
        extend: "copy",
        text: '<i class="fa-solid fa-copy" style="color: #ffffff;margin-right:10px;"></i>Salin',
        exportOptions: {
          columns: ":visible",
        },
      },
      {
        extend: "csv",
        text: '<i class="fa-solid fa-file-csv" style="color: #ffffff; margin-right:10px;"></i>Ekspor ke CSV',
        exportOptions: {
          columns: ":visible",
        },
      },
      {
        extend: "excel",
        title: "Data Barang Laboratorium Terpadu Fakultas Ilmu Komputer",
        text: '<i class="fa-solid fa-file-excel" style="color: #ffffff; margin-right:10px;"></i>Ekspor ke Excel',
        exportOptions: {
          columns: ":visible",
        },
      },
      {
        extend: "print",
        title: "",
        text: '<i class="fa-solid fa-print" style="color: #ffffff;  margin-right:10px;"></i>Cetak',
        exportOptions: {
          columns: ":visible",
          stripHtml: false,
          orientation: "landscape",
        },
        customize: function (win) {
          $(win.document.body).prepend(
            '<img src="../img/logo bg putih.svg" style="width:250px;height:250px;">'
          );
          var css =
            "@page { size: A3 landscape; }" +
            "table.dataTable { width: 100% !important; }" +
            "table.dataTable th, table.dataTable td { white-space: nowrap; }",
            head =
              win.document.head || win.document.getElementsByTagName("head")[0],
            style = win.document.createElement("style");

          style.type = "text/css";
          style.media = "print";

          if (style.styleSheet) {
            style.styleSheet.cssText = css;
          } else {
            style.appendChild(win.document.createTextNode(css));
          }

          head.appendChild(style);
        },


      },
      {//edit search


      },
      {
        extend: "colvis",
        text: "Visibilitas kolom",
      },
    ],
    initComplete: function () {
      // Tambahkan kelas khusus ke elemen input Search
      $("#example_filter input").addClass("custom-search");
    },

  });

  //penutup datatable

  table.buttons().container().appendTo("#example_wrapper :eq(0)");

  // set time flasher
  setTimeout(function () {
    $("#flasher").fadeOut("slow");
  }, 3000);

  //ubah profile
  $(".btn-Ubah-profile").on("click", function () {
    const id = $(this).data("id");
    $.ajax({
      url: "http://localhost/Inventaris_Lab/public/Profil/getUbah",
      data: {
        id_user: id,
      },
      method: "post",
      dataType: "json",
      success: function (data) {
        console.log(data);
        $("#id_user").val(data.id_user);
        $("#nama_user").val(data.nama_user);
        $("#no_hp_user").val(data.no_hp_user);
        $("#alamat").val(data.alamat);
        $("#foto").val(data.foto);
      },
    });
  });

  //ubah barang
  $(".btn-tambah-barang").on("click", function () {
    $("#title-barang").html("Tambah Barang");
    $(".body-barang form").attr(
      "action",
      "http://localhost/Inventaris_Lab/public/DetailBarang/tambahBarang"
    );
    const data = "";
    $("#id_barang").val(data.id_barang);
    $("#id_jenis_barang").val(data.sub_barang);
    $("#nama_merek_barang").val(data.id_merek_barang);
    $("#id_kondisi_barang").val(data.kondisi_barang);
    $("#jumlah_barang").val(data.jumlah_barang);
    $("#id_satuan").val(data.satuan);
    $("#deskripsi_barang").val(data.deskripsi_barang);
    $("#tgl_pengadaan_barang").val(data.tgl_pengadaan_barang);
    $("#keterangan_label").val(data.keterangan_label);
    $("#id_lokasi_penyimpanan").val(data.lokasi_penyimpanan);
    $("#deskripsi_detail_lokasi").val(data.deskripsi_detail_lokasi);
    $("#status").val(data.id_status);
    $("#status_peminjaman").val(data.status_pinjam);
    $("#barang_ke").val(data.barang_ke);
    $("#total_barang").val(data.total_barang);
  });

  $(".tampilBarangUbah").on("click", function () {
    $("#title-barang").html("Ubah Data Barang");
    $(".body-barang form").attr(
      "action",
      "http://localhost/Inventaris_Lab/public/DetailBarang/ubahBarang"
    );
    const id = $(this).data("id");
    console.log(id);
    $.ajax({
      url: "http://localhost/Inventaris_Lab/public/DetailBarang/getUbah",
      data: {
        id_barang: id,
      },
      method: "post",
      dataType: "json",
      success: function (data) {
        $("#id_barang").val(data.id_barang);
        $("#id_jenis_barang").val(data.sub_barang);
        $("#nama_merek_barang").val(data.id_merek_barang);
        $("#id_kondisi_barang").val(data.kondisi_barang);
        $("#jumlah_barang").val(data.jumlah_barang);
        $("#id_satuan").val(data.satuan);
        $("#deskripsi_barang").val(data.deskripsi_barang);
        $("#tgl_pengadaan_barang").val(data.tgl_pengadaan_barang);
        $("#keterangan_label").val(data.keterangan_label);
        $("#id_lokasi_penyimpanan").val(data.lokasi_penyimpanan);
        $("#deskripsi_detail_lokasi").val(data.deskripsi_detail_lokasi);
        $("#status").val(data.id_status);
        $("#status_peminjaman").val(data.status_pinjam);
        $("#barang_ke").val(data.barang_ke);
        $("#total_barang").val(data.total_barang);
      },
    });
  });

  //ubah role
  $(".btnUbahRole").on("click", function () {
    const id_user = $(this).data("user");
    $.ajax({
      url: "http://localhost/Inventaris_Lab/public/KelolaAkun/getRole", // Ubah URL sesuai dengan kebutuhan Anda
      data: {
        id_user: id_user,
      },
      method: "post",
      dataType: "json",
      success: function (data) {
        $("#id_user").val(data.id_user);
        $("#id_role").val(data.id_role);
      },
    });
  });
});

const selectAllCheckbox = document.getElementById("selectAllCheckbox");
const checkboxes = document.querySelectorAll(".checkbox");
selectAllCheckbox.addEventListener("change", function () {
  checkboxes.forEach((checkbox) => {
    checkbox.checked = selectAllCheckbox.checked;
  });
});

document.addEventListener("DOMContentLoaded", function () {
  let currentPath = window.location.pathname.replace(/^\/|\/$/g, "");

  document.querySelectorAll(".menu ul li").forEach((item) => {
    let button = item.querySelector("button");

    if (button) {
      let targetPath = button.getAttribute("onclick").match(/'([^']+)'/)[1];

      if (currentPath.includes(targetPath.replace("<?=BASEURL;?>", "").toLowerCase())) {
        item.classList.add("active");
      }
    }
  });
});

function tampilkanCatatan() {
  var form = document.getElementById('formTolak');
  if (form.style.display === "none") {
    form.style.display = "block";
    form.scrollIntoView({ behavior: "smooth" });
  } else {
    form.style.display = "none";
  }
}

function toggleFormTolak() {
  var formContainer = document.getElementById("formTolakContainer");
  if (formContainer.style.display === "none") {
    formContainer.style.display = "block";
    setTimeout(() => {
      formContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }, 100);
  } else {
    formContainer.style.display = "none";
  }
}

$(document).ready(function () {
  $('[data-toggle="tooltip"]').tooltip();

  var table = $('#tableRiwayat').DataTable({
    "dom": 'rtp',
    "pageLength": 10,
    "ordering": true,
    "columnDefs": [
      { "orderable": false, "targets": 0 },
      { "orderable": false, "targets": 5 },
      { "orderable": false, "targets": 6 }
    ],
    "language": {
      "emptyTable": "Tidak ada data tersedia",
      "info": "Menampilkan _START_ - _END_ dari _TOTAL_ data",
      "infoEmpty": "Menampilkan 0 data",
      "infoFiltered": "(difilter dari _MAX_ total data)",
      "paginate": {
        "first": "Pertama",
        "last": "Terakhir",
        "next": "Selanjutnya",
        "previous": "Sebelumnya"
      }
    }
  });

  $('#customSearch').on('keyup', function () {
    table.search(this.value).draw();
  });

  var statusCycle = ['Melengkapi Surat', 'Diproses', 'Diterima', 'Dikembalikan', 'Ditolak'];
  var currentIndex = 0;

  $('#th-status').on('click', function () {
    if (currentIndex >= statusCycle.length) {
      table.column(5).search('').draw();
      $('#th-status').html('Status <i class="fas fa-filter ml-1" style="font-size: 10px; opacity: 0.7;"></i>');
      currentIndex = 0;
    } else {
      var currentStatus = statusCycle[currentIndex];
      table.column(5).search(currentStatus).draw();
      $('#th-status').html(currentStatus + ' <i class="fas fa-check-circle ml-1"></i>');
      currentIndex++;
    }
  });

  table.on('draw', function () {
    $('[data-toggle="tooltip"]').tooltip();
  });
});

$(document).ready(function () {
  $('[data-toggle="tooltip"]').tooltip();

  var table = $('#tableRiwayat').DataTable({
    "dom": 'rtp',
    "pageLength": 10,
    "ordering": true,
    "columnDefs": [
      { "orderable": false, "targets": 0 },
      { "orderable": false, "targets": 5 },
      { "orderable": false, "targets": 6 }
    ],
    "language": {
      "emptyTable": "Tidak ada data tersedia",
      "info": "Menampilkan _START_ - _END_ dari _TOTAL_ data",
      "infoEmpty": "Menampilkan 0 data",
      "infoFiltered": "(difilter dari _MAX_ total data)",
      "paginate": {
        "first": "Pertama",
        "last": "Terakhir",
        "next": "Selanjutnya",
        "previous": "Sebelumnya"
      }
    }
  });

  $('#customSearch').on('keyup', function () {
    table.search(this.value).draw();
  });

  var statusCycle = ['Melengkapi Surat', 'Diproses', 'Diterima', 'Dikembalikan', 'Ditolak'];
  var currentIndex = 0;

  $('#th-status').on('click', function () {
    if (currentIndex >= statusCycle.length) {
      table.column(5).search('').draw();
      $('#th-status').html('Status <i class="fas fa-filter ml-1" style="font-size: 10px; opacity: 0.7;"></i>');
      currentIndex = 0;
    } else {
      var currentStatus = statusCycle[currentIndex];
      table.column(5).search(currentStatus).draw();
      $('#th-status').html(currentStatus + ' <i class="fas fa-check-circle ml-1"></i>');
      currentIndex++;
    }
  });

  table.on('draw', function () {
    $('[data-toggle="tooltip"]').tooltip();
  });
});

$(document).ready(function () {
  $('#tableValidasi').DataTable({
    // Konfigurasi Bahasa Indonesia
    language: {
      search: "Cari:",
      lengthMenu: "Tampilkan _MENU_ data",
      info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
      infoEmpty: "Tidak ada data",
      infoFiltered: "(difilter dari _MAX_ total data)",
      zeroRecords: "Data tidak ditemukan",
      paginate: {
        first: "<<",
        last: ">>",
        next: ">",
        previous: "<"
      }
    },
    order: [[2, 'desc']],
    columnDefs: [
      { orderable: false, targets: [0, 7] }
    ]
  });
});

const fileInput = document.getElementById('file_surat');
const fileLabel = document.getElementById('file-label');
const dropZone = document.getElementById('drop-zone');
const btnSubmit = document.getElementById('btn-submit');

fileInput.addEventListener('change', function (e) {
  if (this.files && this.files.length > 0) {
    const file = this.files[0];
    const fileName = file.name;
    const fileSize = (file.size / 1024 / 1024).toFixed(2);

    fileLabel.innerHTML = `
                <i class="fas fa-check-circle mr-2" style="color: var(--success-green);"></i>
                ${fileName}
            `;

    dropZone.classList.add('success');

    const subtitle = dropZone.querySelector('.upload-subtitle');
    subtitle.innerHTML = `
                <i class="fas fa-file-alt mr-2"></i>
                Ukuran: ${fileSize} MB
            `;

    const icon = dropZone.querySelector('.upload-icon-wrapper i');
    icon.className = 'fas fa-check-circle';

    btnSubmit.disabled = false;
  }
});

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
  dropZone.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
  e.preventDefault();
  e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
  dropZone.addEventListener(eventName, () => {
    dropZone.classList.add('dragover');
  }, false);
});

['dragleave', 'drop'].forEach(eventName => {
  dropZone.addEventListener(eventName, () => {
    dropZone.classList.remove('dragover');
  }, false);
});

dropZone.addEventListener('drop', function (e) {
  const dt = e.dataTransfer;
  const files = dt.files;
  fileInput.files = files;

  const event = new Event('change', { bubbles: true });
  fileInput.dispatchEvent(event);
});

document.addEventListener("DOMContentLoaded", function () {
  const modal = document.getElementById('modalHapus');
  const btnLinkHapus = document.getElementById('btnLinkHapus');

  window.konfirmasiHapus = function (url) {
    if (modal && btnLinkHapus) {
      btnLinkHapus.setAttribute('href', url);
      modal.classList.add('show');
    }
  };
  window.tutupModal = function () { if (modal) modal.classList.remove('show'); };
  window.onclick = function (event) { if (event.target == modal) tutupModal(); };
});