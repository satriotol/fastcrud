<!doctype html>
<html lang="en" dir="ltr">

<head>

    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="INSPEKTORAT SEMARANG">
    <meta name="author" content="Spruko Technologies Private Limited">
    <meta name="keywords"
        content="admin,admin dashboard,admin panel,admin template,bootstrap,clean,dashboard,flat,jquery,modern,responsive,premium admin templates,responsive admin,ui,ui kit.">

    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('backend_assets/images/LambangSemarang.png') }}" />

    <!-- TITLE -->
    <title>SEMARANG</title>

    <!-- BOOTSTRAP CSS -->
    <link id="style" href="{{ asset('backend_assets/plugins/bootstrap/css/bootstrap.min.css') }}"
        rel="stylesheet" />

    <!-- STYLE CSS -->
    <link href="{{ asset('backend_assets/css/style.css') }}" rel="stylesheet" />

    <!--- FONT-ICONS CSS -->
    <link href="{{ asset('backend_assets/css/icons.css') }}" rel="stylesheet" />

    <!-- COLOR SKIN CSS -->
    <link id="theme" rel="stylesheet" type="text/css" media="all"
        href="{{ asset('backend_assets/colors/color1.css') }}" />
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css"
        rel="stylesheet">
    <link href="https://unpkg.com/filepond/dist/filepond.min.css" rel="stylesheet">

    @stack('style')
    <style>
        .note-group-select-from-files {
            display: none;
        }

        .pagination-container {
            text-align: right;
        }
    </style>

</head>

<body class="app sidebar-mini ltr">

    <!-- GLOBAL-LOADER -->
    <div id="global-loader">
        <img src="{{ asset('backend_assets/images/loader.svg') }}" class="loader-img" alt="Loader">
    </div>
    <!-- /GLOBAL-LOADER -->

    <!-- PAGE -->
    <div class="page">
        <div class="page-main">
            @include('backend_layouts.header')
            @include('backend_layouts.sidebar')
            <div class="main-content app-content mt-0">
                <div class="side-app">
                    <div class="main-container container-fluid">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
        <!-- FOOTER -->
        @include('backend_layouts.footer')
        <!-- FOOTER CLOSED -->
    </div>

    <!-- BACK-TO-TOP -->
    <a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>

    <!-- JQUERY JS -->
    <script src="{{ asset('backend_assets/js/jquery.min.js') }}"></script>

    <!-- BOOTSTRAP JS -->
    <script src="{{ asset('backend_assets/plugins/bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ asset('backend_assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>

    <!-- SPARKLINE JS-->
    <script src="{{ asset('backend_assets/js/jquery.sparkline.min.js') }}"></script>

    <!-- SIDE-MENU JS -->
    <script src="{{ asset('backend_assets/plugins/sidemenu/sidemenu.js') }}"></script>

    <!-- Perfect SCROLLBAR JS-->
    <script src="{{ asset('backend_assets/plugins/p-scroll/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('backend_assets/plugins/p-scroll/pscroll.js') }}"></script>
    {{-- <script src="{{ asset('backend_assets/plugins/p-scroll/pscroll-1.js') }}"></script> --}}

    <!-- DATA TABLE JS-->
    <script src="{{ asset('backend_assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend_assets/plugins/datatable/js/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('backend_assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('backend_assets/plugins/datatable/js/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('backend_assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ asset('backend_assets/plugins/datatable/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('backend_assets/plugins/datatable/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('backend_assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('backend_assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('backend_assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('backend_assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('backend_assets/plugins/datatable/responsive.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('backend_assets/js/table-data.js') }}"></script>

    <!-- SIDEBAR JS -->
    <script src="{{ asset('backend_assets/plugins/sidebar/sidebar.js') }}"></script>

    <!-- Color Theme js -->
    <script src="{{ asset('backend_assets/js/themeColors.js') }}"></script>

    <!-- Sticky js -->
    <script src="{{ asset('backend_assets/js/sticky.js') }}"></script>

    <!-- INTERNAL SUMMERNOTE Editor JS -->
    <script src="{{ asset('backend_assets/plugins/summernote/summernote1.js') }}"></script>
    <script src="{{ asset('backend_assets/js/summernote.js') }}"></script>

    <!-- CUSTOM JS -->
    <script src="{{ asset('backend_assets/plugins/notify/js/rainbow.js') }}"></script>
    <script src="{{ asset('backend_assets/plugins/notify/js/sample.js') }}"></script>
    <script src="{{ asset('backend_assets/plugins/notify/js/jquery.growl.js') }}"></script>
    <script src="{{ asset('backend_assets/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ asset('backend_assets/js/custom.js') }}"></script>
    <script src="{{ asset('backend_assets/plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('backend_assets/js/select2.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.16/dist/sweetalert2.all.min.js"></script>
    @if (session()->has('success'))
        <script>
            notif({
                msg: "<b>Sukses:</b> Proses Anda Berhasil",
                type: "success"
            });
        </script>
    @endif
    @if (session()->has('bug'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session()->get('bug') }}',
            })
        </script>
    @endif
    <script>
        $(function() {
            $('.form-group:has(input[required]) > label')
                .after('<span class="text-red">*</span>')
            $('.form-group:has(select[required]) > label')
                .after('<span class="text-red">*</span>')
            $('.form-group:has(textarea[required]) > label')
                .after('<span class="text-red">*</span>')
        })
    </script>

    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>

    <script>
        const inputElement = document.querySelector('#filepond');
        const pond = FilePond.create(inputElement);

        pond.setOptions({
            server: {
                process: '{{ route('upload.store') }}',
                revert: '{{ route('upload.revert') }}'
            }
        });
    </script>
    @stack('custom-scripts')
</body>

</html>
