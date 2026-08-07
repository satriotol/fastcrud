{{-- Halaman 503 sengaja berdiri sendiri tanpa layout: layout aplikasi butuh
     Helper::appClasses() dan config hasil publish, kalau ada yang kurang
     halaman maintenance malah balas 500. --}}
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sedang Dalam Perbaikan</title>
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f5f5f9;
            color: #384551;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            text-align: center;
            padding: 1.5rem;
        }

        .box {
            max-width: 32rem;
        }

        .icon {
            font-size: 4rem;
            line-height: 1;
            margin-bottom: 1rem;
        }

        h1 {
            font-size: 1.5rem;
            margin: 0 0 .75rem;
        }

        p {
            margin: 0 0 1.5rem;
            color: #6f7b87;
            line-height: 1.6;
        }

        a {
            display: inline-block;
            padding: .5rem 1.25rem;
            border-radius: .375rem;
            background: #696cff;
            color: #fff;
            text-decoration: none;
        }

        @media (prefers-color-scheme: dark) {
            body {
                background: #232333;
                color: #cfd3ec;
            }

            p {
                color: #a3a4cc;
            }
        }
    </style>
</head>

<body>
    <div class="box">
        <div class="icon">&#128736;</div>
        <h1>Sedang Dalam Perbaikan</h1>
        <p>{{ $message }}</p>
        <a href="{{ route('login') }}">Masuk sebagai Administrator</a>
    </div>
</body>

</html>
