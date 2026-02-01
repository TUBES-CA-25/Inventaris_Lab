function triggerUpload() {
    var input = document.getElementById('file_surat');
    
    if (input.files && input.files.length > 0) {
        return; 
    }
    
    input.click();
}

function updateFileName(input) {
    if (input.files && input.files[0]) {
        var file = input.files[0];
        
        var viewDefault = document.getElementById('view-default');
        var viewPreview = document.getElementById('view-preview');
        var nameDisplay = document.getElementById('filename-display');
        var btnSubmit   = document.getElementById('btn-submit');
        var dropZone    = document.getElementById('drop-zone');

        nameDisplay.textContent = file.name;

        viewDefault.style.display = 'none';
        viewPreview.style.display = 'block';

        if (btnSubmit) {
            btnSubmit.disabled = false;
            btnSubmit.style.opacity = '1';
            btnSubmit.style.cursor = 'pointer';
        }

        dropZone.style.borderColor = '#22c55e'; 
        dropZone.style.background = '#f0fdf4';
        
        dropZone.style.cursor = 'default';
        dropZone.onclick = null; 
    }
}

function resetUpload(event) {
    if (event) {
        event.stopPropagation();
        event.preventDefault();
    }

    var input       = document.getElementById('file_surat');
    var viewDefault = document.getElementById('view-default');
    var viewPreview = document.getElementById('view-preview');
    var btnSubmit   = document.getElementById('btn-submit');
    var dropZone    = document.getElementById('drop-zone');

    input.value = '';

    viewPreview.style.display = 'none';
    viewDefault.style.display = 'block';

    if (btnSubmit) {
        btnSubmit.disabled = true;
        btnSubmit.style.opacity = '0.6';
        btnSubmit.style.cursor = 'not-allowed';
    }

    dropZone.style.borderColor = ''; 
    dropZone.style.background = '';
    
    dropZone.style.cursor = 'pointer';
    dropZone.onclick = triggerUpload;
}