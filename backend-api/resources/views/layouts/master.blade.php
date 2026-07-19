<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.jpg') }}">
    {{-- Dynamic SEO --}}
    <title>@yield('title', 'FNF Trip - Travel Agency')</title>
    <meta name="description" content="@yield('description', 'Best travel agency in Bangladesh. Book group and private tours easily.')">
    <meta name="keywords" content="@yield('keywords', 'travel agency bd, tour bd, group tour, private tour')">
    <meta name="author" content="FNF Trip">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph (Facebook / WhatsApp) --}}
    <meta property="og:title" content="@yield('og_title', 'FNF Trip Travel Agency')">
    <meta property="og:description" content="@yield('og_description', 'Explore Bangladesh with amazing tours')">
    <meta property="og:image" content="@yield('og_image', asset('assets/images/seo.png'))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    {{-- Twitter SEO --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', 'FNF Trip Travel Agency')">
    <meta name="twitter:description" content="@yield('twitter_description', 'Best travel agency in Bangladesh')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('images/seo.jpg'))">





    {{-- all cdn link --}}

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @stack('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
</head>

<body>

    @include('partials.navbar')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800
        });
    </script>
    @stack('scripts')

</body>

</html>
