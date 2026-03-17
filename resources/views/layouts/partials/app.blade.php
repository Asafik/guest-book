<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $pageTitle ?? '' }} - {{ $globalSetting->institution_short ?? '' }}</title>

    {{-- Favicon --}}
    @if($globalSetting && $globalSetting->favicon)
        <link rel="icon" type="image/png" href="{{ asset('storage/'.$globalSetting->favicon) }}">
    @else
        <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">
    @endif

    {{-- CSS Global Landing Page --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    {{-- CSS Admin Dashboard --}}
    <link rel="stylesheet" href="{{ asset('admin/css/app.css') }}">

    {{-- Font Awesome lokal --}}
    <link rel="stylesheet" href="{{ asset('icon/css/all.min.css') }}">

    {{-- Google Fonts tetap CDN --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @stack('styles')
</head>
<body>
    @yield('content')

    @stack('scripts')

    {{-- JS Global Landing Page --}}
    <script src="{{ asset('js/app.js') }}"></script>

    {{-- JS Admin Dashboard --}}
    <script src="{{ asset('admin/js/app.js') }}"></script>
</body>
</html>
