/**
 * Global SweetAlert2 Helper Functions
 * Requires SweetAlert2 to be loaded before this script.
 */

const SwalCustom = Swal.mixin({
    customClass: {
        confirmButton: 'btn btn-success ms-2',
        cancelButton: 'btn btn-danger',
        denyButton: 'btn btn-secondary'
    },
    buttonsStyling: false
});

/**
 * Show a success alert
 */
function showSuccess(message, title = 'Berhasil!') {
    return SwalCustom.fire({
        icon: 'success',
        title: title,
        text: message,
        timer: 3000,
        timerProgressBar: true,
        showConfirmButton: false
    });
}

/**
 * Show an error alert
 */
function showError(message, title = 'Gagal!') {
    return SwalCustom.fire({
        icon: 'error',
        title: title,
        text: message,
        showConfirmButton: true,
        confirmButtonText: 'Tutup'
    });
}

/**
 * Show an info alert
 */
function showInfo(message, title = 'Informasi') {
    return SwalCustom.fire({
        icon: 'info',
        title: title,
        text: message,
        showConfirmButton: true,
        confirmButtonText: 'Tutup'
    });
}

/**
 * Show a confirmation dialog
 * Returns a Promise that resolves to true if confirmed, false otherwise.
 */
function showConfirm(message, title = 'Apakah Anda yakin?', confirmText = 'Ya', cancelText = 'Batal') {
    return SwalCustom.fire({
        title: title,
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: confirmText,
        cancelButtonText: cancelText,
        reverseButtons: true
    }).then((result) => {
        return result.isConfirmed;
    });
}

/**
 * Show a loading spinner
 */
function showLoading(title = 'Memproses...') {
    return SwalCustom.fire({
        title: title,
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
}

/**
 * Close the current alert
 */
function closeAlert() {
    Swal.close();
}
