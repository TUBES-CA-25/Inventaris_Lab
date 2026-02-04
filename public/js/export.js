

$(document).ready(function () {
    var headerTitle = "LAPORAN INVENTARIS BARANG";
    var headerDate = "Dicetak pada: <?php echo date('d F Y, H:i'); ?>";
    var headerUser = "User: <?php echo isset($_SESSION['nama_user']) ? $_SESSION['nama_user'] : 'Admin'; ?>";

    var logoBase64 = null;
    var imgLogo = document.getElementById('logoImage');
    if (imgLogo) {
        var canvas = document.createElement("canvas");
        canvas.width = imgLogo.naturalWidth || 500;
        canvas.height = imgLogo.naturalHeight || 150;
        var ctx = canvas.getContext("2d");
        ctx.drawImage(imgLogo, 0, 0);
        try { logoBase64 = canvas.toDataURL("image/png"); } catch (e) { }
    }

    var table = $('#tableExport').DataTable({
        dom: 'Bfrtip',
        paging: false,
        ordering: false,
        searching: false,
        buttons: [
            {
                extend: 'excelHtml5',
                title: headerTitle,
                messageTop: headerDate + " | " + headerUser,
                exportOptions: {
                    columns: ':visible',
                    format: { body: function (d, r, c) { return c >= 12 ? '' : d; } }
                }
            },
            {
                extend: 'pdfHtml5',
                title: '',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                exportOptions: {
    columns: ':visible',
    stripHtml: false,
    format: {
        // Inside export.js -> buttons -> pdfHtml5 -> exportOptions -> format -> body
body: function (data, row, column, node) {
    if ($(node).find('img').length > 0) {
        var img = $(node).find('img')[0];
        
        // 1. Check if image is actually loaded and has dimensions
        // A broken image will have naturalWidth = 0
        if (!img.complete || img.naturalWidth === 0) {
            return ''; 
        }

        try {
            var canvas = document.createElement("canvas");
            canvas.width = img.naturalWidth;
            canvas.height = img.naturalHeight;
            var ctx = canvas.getContext("2d");
            
            // 2. Extra safety: Only draw if dimensions are valid
            if (canvas.width > 0 && canvas.height > 0) {
                ctx.drawImage(img, 0, 0);
                return 'IMAGE:' + canvas.toDataURL("image/png");
            }
            return '';
        } catch (e) {
            console.warn("Skipping broken or restricted image at row " + row, e);
            return ''; 
        }
    }
    return data.replace(/<[^>]+>/g, '').trim();
}
    }
},
                customize: function (doc) {
                    // [PERUBAHAN] Logo PDF diperbesar (width: 250)
                    var headerContent = {
                        margin: [0, 0, 0, 10],
                        columns: [
                            { image: logoBase64, width: 250, alignment: 'left' }, // Lebar logo PDF diperbesar
                            {
                                width: '*',
                                stack: [
                                    { text: headerTitle, fontSize: 16, bold: true, color: '#0c1740', alignment: 'right', margin: [0, 10, 0, 0] },
                                    { text: headerDate, fontSize: 10, color: '#555', alignment: 'right' },
                                    { text: headerUser, fontSize: 10, color: '#555', alignment: 'right' }
                                ]
                            }
                        ]
                    };
                    if (!logoBase64) headerContent.columns[0] = { text: 'SISTEM', fontSize: 14 };

                    if (doc.content[0].text === '' && doc.content[0].style === 'title') doc.content.splice(0, 1);
                    doc.content.splice(0, 0, headerContent);
                    doc.content.splice(1, 0, { canvas: [{ type: 'line', x1: 0, y1: 0, x2: 950, y2: 0, lineWidth: 3, lineColor: '#0c1740' }], margin: [0, 0, 0, 15] });

                    var body = doc.content[2].table.body;
                    for (var i = 1; i < body.length; i++) {
                        for (var j = 0; j < body[i].length; j++) {
                            if (body[i][j].text && body[i][j].text.startsWith('IMAGE:')) {
                                body[i][j] = { image: body[i][j].text.replace('IMAGE:', ''), width: 30, height: 30, alignment: 'center' };
                            } else {
                                body[i][j].alignment = 'center';
                            }
                        }
                    }
                }
            },
            {
                extend: 'print',
                autoPrint: true,
                title: '',
                exportOptions: {
                    columns: ':visible',
                    stripHtml: false
                },
                customize: function (win) {
                    $(win.document.body).css('font-family', "'Poppins', sans-serif").css('padding', '20px');

                    // [PERUBAHAN] Logo PRINT diperbesar (height: 100px)
                    var logoHtml = logoBase64 ? '<img src="' + logoBase64 + '" style="height: 100px; width: auto;">' : '<h2>SISTEM</h2>';

                    var headerContent =
                        '<div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 3px solid #0c1740; padding-bottom: 15px; margin-bottom: 20px;">' +
                        '<div>' + logoHtml + '</div>' +
                        '<div style="text-align: right;">' +
                        '<h2 style="margin: 0; color: #0c1740; font-size: 20px; font-weight: 700; text-transform: uppercase;">' + headerTitle + '</h2>' +
                        '<p style="margin: 5px 0 0; font-size: 12px; color: #555;">' + headerDate + '</p>' +
                        '<p style="margin: 0; font-size: 12px; color: #555;">' + headerUser + '</p>' +
                        '</div>' +
                        '</div>';

                    $(win.document.body).prepend(headerContent);

                    $(win.document.body).find('table').addClass('compact').css('width', '100%').css('border-collapse', 'collapse').css('font-size', '10px');
                    $(win.document.body).find('table thead th').css('background-color', '#0c1740').css('color', 'white').css('text-align', 'center').css('padding', '8px').css('border', '1px solid #0c1740');
                    $(win.document.body).find('table tbody td').css('text-align', 'center').css('vertical-align', 'middle').css('padding', '5px').css('border', '1px solid #ccc');
                    $(win.document.body).find('img').not('.header-laporan img').css('width', '40px').css('height', '40px').css('object-fit', 'contain');
                    $(win.document.body).find('h1').remove();
                }
            }
        ]
    });

    $('#triggerExcel').on('click', function () { table.button(0).trigger(); });
    $('#triggerPdf').on('click', function () { table.button(1).trigger(); });
    $('#triggerPrint').on('click', function () { table.button(2).trigger(); });
});

