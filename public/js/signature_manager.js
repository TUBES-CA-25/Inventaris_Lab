/**
 * SignatureManager - A class to handle PDF rendering and signature positioning.
 */
class SignatureManager {
    constructor(config) {
        this.url = config.url;
        this.pdfContainerId = config.pdfContainerId;
        this.loaderId = config.loaderId;
        this.dragBoxId = config.dragBoxId;
        this.pageWidth = config.pageWidth || 850;
        this.pdfContainer = document.getElementById(this.pdfContainerId);
        this.pagesMetaData = [];
        this.pdfDoc = null;
    }

    async init() {
        try {
            this.updateLoaderStatus('Mengunduh Dokumen...');
            const loadingTask = pdfjsLib.getDocument(this.url);

            loadingTask.onProgress = (progress) => {
                if (progress.total > 0) {
                    let percent = Math.round((progress.loaded / progress.total) * 100);
                    this.updateLoaderStatus(`Mengunduh Dokumen (${percent}%)...`);
                }
            };

            this.pdfDoc = await loadingTask.promise;
            await this.processMetadata();
            this.hideLoader();
            this.showDragBox();

            // Progressive background rendering
            this.renderPages();
        } catch (error) {
            console.error('PDF Init Error:', error);
            Swal.fire('Error', 'Gagal memuat PDF: ' + error.message, 'error');
        }
    }

    async processMetadata() {
        this.updateLoaderStatus('Menghitung Layout Halaman...');
        let currentTop = 0;

        for (let pageNum = 1; pageNum <= this.pdfDoc.numPages; pageNum++) {
            const page = await this.pdfDoc.getPage(pageNum);
            const viewport = page.getViewport({ scale: 1.0 });

            let displayWidth = Math.min(this.pageWidth, this.pdfContainer.offsetWidth || this.pageWidth);
            let scale = displayWidth / viewport.width;
            let displayHeight = viewport.height * scale;

            // Create placeholder canvas
            let canvas = document.createElement('canvas');
            canvas.className = 'pdf-page';
            canvas.id = 'page-' + pageNum;
            canvas.width = viewport.width;
            canvas.height = viewport.height;
            this.pdfContainer.appendChild(canvas);

            this.pagesMetaData.push({
                pageNumber: pageNum,
                height: displayHeight,
                width: displayWidth,
                top: currentTop,
                pageObj: page,
                viewport: viewport,
                canvas: canvas
            });

            currentTop += displayHeight + 12; // 12 is margin-bottom + border
        }
    }

    async renderPages() {
        for (let meta of this.pagesMetaData) {
            const ctx = meta.canvas.getContext('2d', { alpha: false, desynchronized: true });
            const renderContext = {
                canvasContext: ctx,
                viewport: meta.viewport,
                enableWebGL: true
            };

            await meta.pageObj.render(renderContext).promise;
            meta.canvas.classList.add('loaded');

            // Final sync of positions
            meta.top = meta.canvas.offsetTop;
            meta.height = meta.canvas.offsetHeight;
            meta.width = meta.canvas.offsetWidth;
        }
    }

    updateLoaderStatus(text) {
        const p = document.querySelector(`#${this.loaderId} p`);
        if (p) p.innerText = text;
    }

    hideLoader() {
        const loader = document.getElementById(this.loaderId);
        if (loader) loader.style.display = 'none';
    }

    showDragBox() {
        const dragBox = document.getElementById(this.dragBoxId);
        if (dragBox) {
            dragBox.style.display = 'block';
            this.initSignaturePosition(1, 0.15, 0.75);
        }
    }

    initSignaturePosition(pageNum, percentX, percentY) {
        let pageData = this.pagesMetaData[pageNum - 1];
        if (!pageData) return;

        let el = document.getElementById(this.dragBoxId);
        let x = pageData.width * percentX;
        let y = pageData.top + (pageData.height * percentY);

        this.updateElementTransform(el, x, y);
    }

    updateElementTransform(el, x, y) {
        el.setAttribute('data-x', x);
        el.setAttribute('data-y', y);
        el.style.transform = `translate(${x}px, ${y}px)`;
    }

    setupInteractions() {
        interact('.drag-box')
            .draggable({
                listeners: {
                    start: (event) => event.target.classList.add('active'),
                    move: (event) => {
                        let target = event.target;
                        let x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx;
                        let y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy;
                        this.updateElementTransform(target, x, y);
                    },
                    end: (event) => event.target.classList.remove('active')
                },
                modifiers: [
                    interact.modifiers.restrictRect({
                        restriction: '#pdf-wrapper',
                        endOnly: false
                    })
                ]
            })
            .resizable({
                edges: { left: true, right: true, bottom: true, top: true },
                listeners: {
                    start: (event) => event.target.classList.add('active'),
                    move: (event) => {
                        let target = event.target;
                        let x = (parseFloat(target.getAttribute('data-x')) || 0);
                        let y = (parseFloat(target.getAttribute('data-y')) || 0);

                        target.style.width = event.rect.width + 'px';
                        target.style.height = event.rect.height + 'px';

                        x += event.deltaRect.left;
                        y += event.deltaRect.top;

                        this.updateElementTransform(target, x, y);
                    },
                    end: (event) => event.target.classList.remove('active')
                },
                modifiers: [
                    interact.modifiers.restrictEdges({ outer: '#pdf-wrapper' }),
                    interact.modifiers.restrictSize({ min: { width: 50, height: 20 } })
                ],
                inertia: true
            });
    }

    getFinalCoordinates() {
        const el = document.getElementById(this.dragBoxId);
        const absX = parseFloat(el.getAttribute('data-x')) || 0;
        const absY = parseFloat(el.getAttribute('data-y')) || 0;
        const absW = el.offsetWidth;
        const absH = el.offsetHeight;

        let targetPage = null;
        const checkY = absY + (absH / 2);

        for (let p of this.pagesMetaData) {
            if (checkY >= p.top && checkY <= (p.top + p.height + 10)) {
                targetPage = p;
                break;
            }
        }

        if (!targetPage) targetPage = this.pagesMetaData[this.pagesMetaData.length - 1];

        const relativeY = absY - targetPage.top;

        return {
            page: targetPage.pageNumber,
            x: (absX / targetPage.width).toFixed(4),
            y: (relativeY / targetPage.height).toFixed(4),
            w: (absW / targetPage.width).toFixed(4),
            h: (absH / targetPage.height).toFixed(4)
        };
    }
}
