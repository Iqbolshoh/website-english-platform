function Logout() {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this action!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, log out!',
        cancelButtonText: 'No, cancel!',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '../logout/';
        }
    });
}