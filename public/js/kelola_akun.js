// Externalized JS for Kelola_akun view
function setModalData(id) {
    document.getElementById('id_user_modal').value = id;
}

// jQuery handler to support existing HTML that triggers modal
$(document).on('click', '.btnUbahRole', function () {
    var id = $(this).data('user');
    if (id) $('#id_user_modal').val(id);
});
