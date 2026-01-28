function triggerUpload() {
    document.getElementById('file_surat').click();
}

function updateFileName(input) {
    if (input.files && input.files[0]) {
        var file = input.files[0];
        var viewDefault = document.getElementById('view-default');
        var viewPreview = document.getElementById('view-preview');
        var nameDisplay = document.getElementById('filename-display');
        var btnSubmit = document.getElementById('btn-submit');
        var dropZone = document.getElementById('drop-zone');

        nameDisplay.textContent = file.name;

        viewDefault.style.display = 'none';
        viewPreview.style.display = 'block';

        btnSubmit.disabled = false;
        btnSubmit.style.opacity = '1';
        btnSubmit.style.cursor = 'pointer';

        dropZone.style.borderColor = 'var(--success-green)';
        dropZone.style.background = '#f0fdf4';
    }
}

function resetUpload(event) {
    event.stopPropagation();

    var input = document.getElementById('file_surat');
    var viewDefault = document.getElementById('view-default');
    var viewPreview = document.getElementById('view-preview');
    var btnSubmit = document.getElementById('btn-submit');
    var dropZone = document.getElementById('drop-zone');

    input.value = '';

    viewPreview.style.display = 'none';
    viewDefault.style.display = 'block';

    btnSubmit.disabled = true;
    btnSubmit.style.opacity = '0.6';
    btnSubmit.style.cursor = 'not-allowed';

    dropZone.style.borderColor = '';
    dropZone.style.background = '';
}