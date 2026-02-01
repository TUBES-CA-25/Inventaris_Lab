// Register JS Functions

function limitSize() {
    const fileInput = document.getElementById('foto');
    const file = fileInput.files[0];
    
    if (file) {
        const maxSizeInBytes = 2 * 1024 * 1024; // 2 MB
        
        if (file.size > maxSizeInBytes) {
            alert('Ukuran file terlalu besar. Maksimal 2 MB.');
            fileInput.value = ''; // Clear the input
            return false;
        }
    }
    return true;
}

// Form validation for password confirmation
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const konfirmasiInput = document.getElementById('konfirmasi-password');
    const errorMessage = document.getElementById('error_message');
    
    if (passwordInput && konfirmasiInput && errorMessage) {
        // Check on input event
        [passwordInput, konfirmasiInput].forEach(input => {
            input.addEventListener('input', function() {
                if (passwordInput.value && konfirmasiInput.value) {
                    if (passwordInput.value !== konfirmasiInput.value) {
                        errorMessage.textContent = 'Password tidak cocok';
                        errorMessage.style.display = 'block';
                    } else {
                        errorMessage.textContent = '';
                        errorMessage.style.display = 'none';
                    }
                }
            });
        });
    }
});
