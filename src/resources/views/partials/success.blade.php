@if (session()->has('success'))
    <style>
        .swal2-container {
            z-index: 999999999;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            title: "Sukses",
            text: "{{ session()->get('success') }}",
            icon: "success"
        });
    </script>
@endif
