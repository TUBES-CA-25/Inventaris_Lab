// Externalized JS for DetailBarang form
const baseUrl_form = document.querySelector('meta[name="base-url"]')?.getAttribute('content') || (window.BASEURL || '');

const lastSelectValue = {};

function checkSelection(type) {
    const select = document.getElementById('select-' + type);
    const inputContainer = document.getElementById('input-container-' + type);
    const btnDelete = document.getElementById('btn-delete-' + type);

    if (!select || !inputContainer || !btnDelete) {
        console.error('Element tidak ditemukan untuk type:', type);
        return;
    }

    const inputs = inputContainer.querySelectorAll('input');

    if (select.value !== 'NEW' && select.value !== '') {
        lastSelectValue[type] = select.value;
    }

    if (select.value === 'NEW') {
        select.style.display = 'none';
        btnDelete.style.display = 'none';
        inputContainer.style.display = 'block';

        inputs.forEach(input => {
            input.disabled = false;
            input.value = '';
        });

        if (inputs[0]) {
            inputs[0].focus();
        }
        return;
    }

    inputContainer.style.display = 'none';
    select.style.display = 'block';

    if (select.value && select.value !== '') {
        btnDelete.style.display = 'flex';
    } else {
        btnDelete.style.display = 'none';
    }
}

function cancelInput(type) {
    try {
        const inputContainer = document.getElementById('input-container-' + type);
        const select = document.getElementById('select-' + type);
        const btnDelete = document.getElementById('btn-delete-' + type);

        if (!inputContainer || !select || !btnDelete) {
            console.error('Element tidak ditemukan:', {
                inputContainer: !!inputContainer,
                select: !!select,
                btnDelete: !!btnDelete
            });
            return false;
        }

        inputContainer.style.display = 'none';

        const inputs = inputContainer.querySelectorAll('input');
        inputs.forEach(input => {
            if (input) {
                input.value = '';
                input.disabled = true;
            }
        });

        select.style.display = 'block';

        if (lastSelectValue[type]) {
            select.value = lastSelectValue[type];
            btnDelete.style.display = 'flex';
        } else {
            select.value = '';
            btnDelete.style.display = 'none';
        }

        return false;

    } catch (error) {
        console.error('Error di cancelInput:', error);
        return false;
    }
}

function hapusMaster(type) {
    try {
        const select = document.getElementById('select-' + type);
        if (!select || !select.value) {
            console.error('Select tidak ditemukan atau tidak ada nilai');
            return;
        }

        const id = select.value;
        const text = select.options[select.selectedIndex].text.trim();

        Swal.fire({
            title: 'Hapus Data Master?',
            text: "Anda akan menghapus '" + text + "'. Data tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = baseUrl_form + 'DetailBarang/hapusMaster/' + type + '/' + id;
            }
        });

    } catch (error) {
        console.error('Error di hapusMaster:', error);
    }
}

document.addEventListener("DOMContentLoaded", function() {
    const types = ['jenis', 'lokasi', 'status', 'merek', 'satuan'];

    types.forEach(type => {
        try {
            const select = document.getElementById('select-' + type);
            const inputContainer = document.getElementById('input-container-' + type);

            if (!select) {
                console.warn('Select tidak ditemukan untuk:', type);
                return;
            }

            if (select.value && select.value !== 'NEW' && select.value !== '') {
                lastSelectValue[type] = select.value;
            }
 
            if (inputContainer) {
                inputContainer.style.display = 'none';
            }

            checkSelection(type);

        } catch (error) {
            console.error('Error inisialisasi ' + type + ':', error);
        }
    });
});
