function openEditModal() {
    document.getElementById('editModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeEditModal() {
    document.getElementById('editModal').classList.remove('active');
    document.body.style.overflow = 'auto';
}

function openTTDModal() {
    const modal = document.getElementById('ttdModal');
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function closeTTDModal() {
    const modal = document.getElementById('ttdModal');
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }
}

document.getElementById('editModal').addEventListener('click', function (e) {
    if (e.target === this) {
        closeEditModal();
    }
});

// Event listener untuk modal TTD jika ada
const ttdModal = document.getElementById('ttdModal');
if (ttdModal) {
    ttdModal.addEventListener('click', function (e) {
        if (e.target === this) {
            closeTTDModal();
        }
    });
}

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        closeEditModal();
        closeTTDModal();
    }
});

setTimeout(function () {
    const flashMessage = document.querySelector('.flash-message');
    if (flashMessage) {
        flashMessage.style.opacity = '0';
        flashMessage.style.transition = 'opacity 0.5s ease';
        setTimeout(() => flashMessage.remove(), 500);
    }
}, 3000);

function openPasswordModal(){
    document.getElementById('passwordModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closePasswordModal(){
    document.getElementById('passwordModal').classList.remove('active');
    document.body.style.overflow = 'auto';
}

document.getElementById('passwordModal').addEventListener('click', function(e){
    if(e.target === this){
        closePasswordModal();
    }
});
