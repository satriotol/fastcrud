@if (session()->has('success'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            title: "Sukses",
            text: "{{ session()->get('success') }}",
            icon: "success"
        });
    </script>
@endif
