// Riwayat JS Functions

// Image Modal Functions for detail.php
function showImageModal(src, caption) {
    const modal = document.getElementById('imageModal');
    const fullImage = document.getElementById('fullImage');
    const imageCaption = document.getElementById('imageCaption');
    
    if (modal && fullImage) {
        fullImage.src = src;
        imageCaption.textContent = caption || 'Foto Barang';
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }
}

// Close modal when clicking outside the image
document.addEventListener('DOMContentLoaded', function() {
    const imageModal = document.getElementById('imageModal');
    if (imageModal) {
        imageModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });

        // Close on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });
    }
});

// DataTable Error Handler for index.php
$(function() {
    $.fn.dataTable.ext.errMode = 'none';
    $.fn.dataTable.ext.errMode = function(settings, helpPage, message) {
        console.log("DataTables Error: ", message);
    };
});
