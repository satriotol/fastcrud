@if (session()->has('success'))
    <script>
        toastr.success('{{ session()->get('success') }}');
    </script>
@endif
