<!doctype html>
<html lang="en" dir="ltr">

<head>

    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('backend_assets/images/brand/favicon.ico') }}" />

    <!-- TITLE -->
    <title>Login</title>

    <!-- BOOTSTRAP CSS -->
    <link id="style" href="{{ asset('backend_assets/plugins/bootstrap/css/bootstrap.min.css') }}"
        rel="stylesheet" />

    <!-- STYLE CSS -->
    <link href="{{ asset('backend_assets/css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('backend_assets/css/dark-style.css') }}" rel="stylesheet" />
    <link href="{{ asset('backend_assets/css/transparent-style.css') }}" rel="stylesheet">
    <link href="{{ asset('backend_assets/css/skin-modes.css') }}" rel="stylesheet" />

    <!--- FONT-ICONS CSS -->
    <link href="{{ asset('backend_assets/css/icons.css') }}" rel="stylesheet" />

    <!-- COLOR SKIN CSS -->
    <link id="theme" rel="stylesheet" type="text/css" media="all"
        href="{{ asset('backend_assets/colors/color1.css') }}" />

</head>

<body class="app sidebar-mini ltr">

    <!-- BACKGROUND-IMAGE -->
    <div class="login-img" id="app">

        <!-- PAGE -->
        <div class="page">
            <div class="">

                <!-- CONTAINER OPEN -->
                <div class="col col-login mx-auto mt-7">
                    <div class="text-center">
                        <img src="{{ asset('Lambang_Kota_Semarang.png') }}" style="height: 50px"
                            class="header-brand-img" alt="">
                    </div>
                </div>

                <div class="container-login100">
                    <div class="wrap-login100 p-6">
                        <span class="login100-form-title pb-5">
                            Login
                        </span>
                        @include('partials.errors')
                        <div class="panel panel-primary">
                            <div class="panel-body tabs-menu-body p-0 pt-5">
                                <div class="tab-content">
                                    <form @submit.prevent="login">
                                        <div class="tab-pane active" id="tab5">
                                            <div class="wrap-input100 validate-input input-group"
                                                data-bs-validate="Valid email is required: ex@abc.xyz">
                                                <a href="javascript:void(0)"
                                                    class="input-group-text bg-white text-muted">
                                                    <i class="zmdi zmdi-email text-muted" aria-hidden="true"></i>
                                                </a>
                                                <input class="input100 border-start-0 form-control ms-0" type="email"
                                                    v-model="form.email" required placeholder="Email">
                                            </div>
                                            <div class="wrap-input100 validate-input input-group" id="Password-toggle">
                                                <a href="javascript:void(0)"
                                                    class="input-group-text bg-white text-muted">
                                                    <i class="zmdi zmdi-eye text-muted" aria-hidden="true"></i>
                                                </a>
                                                <input class="input100 border-start-0 form-control ms-0" type="password"
                                                    v-model="form.password" required placeholder="Password">
                                            </div>
                                            <div class="form-group mt-4 mb-4">
                                                <div class="captcha">
                                                    <span v-html="captchaImage"></span>
                                                    <button type="button" class="btn btn-danger" class="reload"
                                                        id="reload" @click="reloadCaptcha()">
                                                        &#x21bb;
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="form-group mb-4">
                                                <input id="captcha" type="text" class="form-control"
                                                    placeholder="Enter Captcha" v-model="form.captcha">
                                            </div>
                                            <div class="container-login100-form-btn">
                                                <button type="submit"
                                                    class="login100-form-btn btn-primary">Masuk</button>
                                            </div>
                                            <div class="text-center pt-3">
                                                <p class="text-dark mb-0">Tidak Punya Akun ?<a
                                                        href="{{ route('register') }}"
                                                        class="text-primary ms-1">Daftar</a>
                                                </p>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- CONTAINER CLOSED -->
            </div>
        </div>
        <!-- End PAGE -->

    </div>
    <!-- BACKGROUND-IMAGE CLOSED -->

    <!-- JQUERY JS -->
    <script src="{{ asset('backend_assets/js/jquery.min.js') }}"></script>

    <!-- BOOTSTRAP JS -->
    <script src="{{ asset('backend_assets/plugins/bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ asset('backend_assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>

    <!-- SHOW PASSWORD JS -->
    <script src="{{ asset('backend_assets/js/show-password.min.js') }}"></script>


    <!-- Perfect SCROLLBAR JS-->
    <script src="{{ asset('backend_assets/plugins/p-scroll/perfect-scrollbar.js') }}"></script>

    <!-- Color Theme js -->
    <script src="{{ asset('backend_assets/js/themeColors.js') }}"></script>

    <!-- CUSTOM JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.7/dist/sweetalert2.all.min.js"></script>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.1.3/axios.min.js"
        integrity="sha512-0qU9M9jfqPw6FKkPafM3gy2CBAvUWnYVOfNPDYKVuRTel1PrciTj+a9P3loJB+j0QmN2Y0JYQmkBBS8W+mbezg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        const {
            createApp
        } = Vue

        createApp({
            data() {
                return {
                    message: 'Hello Vue!',
                    form: {
                        email: '',
                        password: '',
                        captcha: '',
                    },
                    captchaImage: '',
                }
            },
            mounted() {
                this.reloadCaptcha();
            },
            methods: {
                login() {
                    Swal.fire({
                        title: 'Mencoba Masuk',
                        icon: 'info',
                        timerProgressBar: true,
                        didOpen: () => {
                            Swal.showLoading()
                        },
                        allowOutsideClick: false
                    });
                    // console.log(this.form);
                    axios.post('/login', this.form)
                        .then((res) => {
                            console.log(res);
                            Swal.fire({
                                title: 'Sukses',
                                icon: 'success',
                                confirmButtonText: 'Lanjut',
                            }).then((result) => {
                                /* Read more about isConfirmed, isDenied below */
                                if (result.isConfirmed) {
                                    window.location.href = res.request.responseURL
                                }
                            })
                        }).catch((err) => {
                            Swal.fire({
                                title: 'Error',
                                icon: 'error',
                                text: err.response.data.message,
                                confirmButtonText: 'Ok',
                            });
                            this.form.captcha = '';
                            this.reloadCaptcha();
                        });
                },
                reloadCaptcha() {
                    axios.get('/reload-captcha')
                        .then((res) => {
                            this.captchaImage = res.data.captcha;
                            console.log(this.captchaImage);
                        });
                }
            },
        }).mount('#app')
    </script>
</body>

</html>
