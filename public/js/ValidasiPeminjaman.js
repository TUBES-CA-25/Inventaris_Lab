const url = '<?= BASEURL; ?>files/surat-peminjaman/<?= $data['file_surat']; ?>';
pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';

const pdfContainer = document.getElementById('pdf-container');
let pagesMetaData = [];

pdfjsLib.getDocument(url).promise.then(async function (pdf) {

    for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
        await pdf.getPage(pageNum).then(function (page) {
            let viewport = page.getViewport({ scale: 1.5 });

            let canvas = document.createElement('canvas');
            canvas.className = 'pdf-page';
            canvas.id = 'page-' + pageNum;
            canvas.width = viewport.width;
            canvas.height = viewport.height;

            let ctx = canvas.getContext('2d');
            let renderContext = {
                canvasContext: ctx,
                viewport: viewport
            };

            pdfContainer.appendChild(canvas);

            return page.render(renderContext).promise.then(() => {
                pagesMetaData.push({
                    pageNumber: pageNum,
                    height: canvas.offsetHeight,
                    width: canvas.offsetWidth,
                    top: canvas.offsetTop
                });
            });
        });
    }

    document.getElementById('loader').style.display = 'none';
    document.querySelectorAll('.drag-box').forEach(el => el.style.display = 'block');

    if (pagesMetaData.length > 0) {
        initPositionOnPage('drag-fatimah', 1, 0.15, 0.75);
        initPositionOnPage('drag-huzain', 1, 0.60, 0.75);
    }

}).catch(function (error) {
    console.error(error);
    alert('Gagal memuat PDF: ' + error.message);
});

function initPositionOnPage(elementId, pageNum, percentX, percentY) {
    let pageData = pagesMetaData[pageNum - 1];
    if (!pageData) return;

    let el = document.getElementById(elementId);

    let x = pageData.width * percentX;
    let y = pageData.top + (pageData.height * percentY);

    el.setAttribute('data-x', x);
    el.setAttribute('data-y', y);
    el.style.transform = `translate(${x}px, ${y}px)`;
}

interact('.drag-box').draggable({
    listeners: {
        move(event) {
            let target = event.target;
            let x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx;
            let y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy;

            target.style.transform = `translate(${x}px, ${y}px)`;
            target.setAttribute('data-x', x);
            target.setAttribute('data-y', y);
        }
    },
    modifiers: [
        interact.modifiers.restrictRect({
            restriction: '#pdf-wrapper',
            endOnly: false
        })
    ]
});

function submitValidasi() {

    function calculatePosition(id) {
        let el = document.getElementById(id);
        let absX = parseFloat(el.getAttribute('data-x')) || 0;
        let absY = parseFloat(el.getAttribute('data-y')) || 0;

        let targetPage = null;

        let checkY = absY + 50;

        for (let i = 0; i < pagesMetaData.length; i++) {
            let p = pagesMetaData[i];
            if (checkY >= p.top && checkY <= (p.top + p.height + 10)) {
                targetPage = p;
                break;
            }
        }

        if (!targetPage) {
            targetPage = pagesMetaData[pagesMetaData.length - 1];
        }

        let relativeY = absY - targetPage.top;

        let percentX = (absX / targetPage.width).toFixed(4);
        let percentY = (relativeY / targetPage.height).toFixed(4);

        return {
            page: targetPage.pageNumber,
            x: percentX,
            y: percentY
        };
    }

    let fatimah = calculatePosition('drag-fatimah');
    let huzain = calculatePosition('drag-huzain');

    document.getElementById('input_page').value = fatimah.page;

    document.getElementById('fatimah_x').value = fatimah.x;
    document.getElementById('fatimah_y').value = fatimah.y;
    document.getElementById('huzain_x').value = huzain.x;
    document.getElementById('huzain_y').value = huzain.y;

    let warningText = "Posisi tanda tangan akan disimpan.";
    if (fatimah.page !== huzain.page) {
        warningText += " Perhatian: Tanda tangan Fatimah ada di Hal " + fatimah.page +
            " sedangkan Huzain di Hal " + huzain.page + ".";
    }

    Swal.fire({
        title: 'Simpan Posisi?',
        text: warningText,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Simpan'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('formTTD').submit();
        }
    });
}