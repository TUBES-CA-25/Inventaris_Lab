document.addEventListener('DOMContentLoaded', function(){
    // file upload helpers
    window.triggerUpload = function(){
        var input = document.getElementById('file_surat');
        if(input) input.click();
    }

    window.updateFileName = function(el){
        var preview = document.getElementById('view-preview');
        var def = document.getElementById('view-default');
        var filename = document.getElementById('filename-display');
        var btnSubmit = document.getElementById('btn-submit');
        if(el.files && el.files.length > 0){
            if(def) def.style.display = 'none';
            if(preview) preview.style.display = 'flex';
            if(filename) filename.textContent = el.files[0].name;
            if(btnSubmit) btnSubmit.disabled = false;
        } else {
            if(def) def.style.display = 'block';
            if(preview) preview.style.display = 'none';
            if(btnSubmit) btnSubmit.disabled = true;
        }
    }

    window.resetUpload = function(e){
        e.preventDefault();
        var input = document.getElementById('file_surat');
        if(input){ input.value = ''; }
        updateFileName(input);
    }

    // protect default form submission UX if needed
    var form = document.querySelector('form');
    if(form){
        form.addEventListener('submit', function(){
            var btn = document.getElementById('btn-submit');
            if(btn){ btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...'; btn.disabled = true; }
        });
    }
});
