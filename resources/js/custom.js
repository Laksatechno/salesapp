$(document).on("click", ".access-failed", function() {
    swal.fire({
        title: "Maaf!",
        text: "Anda tidak memiliki akses!",
        icon: "info",
        timer: 2000,
    });
});