<!doctype html>
<html lang="pt_BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />
    <title>@yield('title')</title>
</head>
<body>
    <div class="d-flex flex-column justify-content-center align-items-center">
        @include('partials.logo')
        <h1>@yield('title')</h1>
        <p>@yield('subject')</p>
    </div>
    <div>
        @yield('content')
    </div>
    @include('partials.footer')
</body>
</html>
