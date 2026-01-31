// External handler for PrintSatu: reads meta filename and triggers html2pdf
document.addEventListener('DOMContentLoaded', function () {
    try {
        var element = document.getElementById('contentToPrint');
        if (!element) return;

        var meta = document.querySelector('meta[name="print-filename"]');
        var raw = meta ? meta.getAttribute('content') : 'Detail';
        var fileName = 'Detail_' + raw + '.pdf';

        var opt = {
            margin: [10, 10, 10, 10],
            filename: fileName,
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, useCORS: true },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' }
        };

        // Start generation after a tiny delay to ensure images load
        setTimeout(function () {
            // Show hidden content to ensure correct rendering then hide again
            var wasHidden = element.classList.contains('offscreen');
            if (wasHidden) {
                element.style.position = 'static';
                element.classList.remove('offscreen');
            }

            html2pdf().set(opt).from(element).save().then(function () {
                // after save, optionally close window or redirect
                // hide content again
                if (wasHidden) {
                    element.classList.add('offscreen');
                    element.style.position = '';
                }

                var loading = document.getElementById('loadingMsg');
                if (loading) loading.style.display = 'none';
            }).catch(function (err) {
                console.error('html2pdf error', err);
            });
        }, 300);

    } catch (e) { console.error(e); }
});
