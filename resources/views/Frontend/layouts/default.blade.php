<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="index, follow">
    <meta name="google-adsense-account" content="ca-pub-4405345005005059">
    <link rel="canonical" href="{{ url()->current() }}" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Mooli&family=Patrick+Hand&family=Roboto+Condensed:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&display=swap"
        rel="stylesheet">


    <!-- Bootstrap CSS v5.2.1 -->
    <link href="{{ asset('assets/frontend/libs/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('assets/frontend/images/favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset(mix('assets/frontend/css/app.css')) }}">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;700&family=Noto+Serif:wght@400;700&display=swap"
        rel="stylesheet">
    <!-- Charter font từ Fontesk -->

    @stack('styles')

    <style>
        @font-face {
            font-family: 'Charter';
            src: url('/path-to-your-fonts/charter.woff2') format('woff2'),
                url('/path-to-your-fonts/charter.woff') format('woff');
            font-weight: normal;
            font-style: normal;
        }
    </style>

    <script>
        window.SuuTruyen = {
            baseUrl: '{{ config('app.url') }}',
            urlCurrent: '{{ url()->current() }}',
            csrfToken: '{{ csrf_token() }}'
        }
    </script>

    @routes()

    @stack('custom_schema')

    @if (isset($setting) && $setting)
        {!! $setting->header_script !!}
    @endif
</head>

<body @if ($bgColorCookie == 'dark') class="dark-theme" @endif>
    @if (isset($setting) && $setting)
        {!! $setting->body_script !!}
    @endif

    @stack('before_content')
    @include('Frontend.layouts.header')


    <main>
        @yield('content')
    </main>

    @include('Frontend.components.floating_tools')

    @include('Frontend.layouts.footer')

    @include('Frontend.layouts.script_default')

    @stack('scripts')

    @if (isset($setting) && $setting)
        {!! $setting->footer_script !!}
    @endif

    @include('Frontend.snippets.loading_full')
    @include('Frontend.components.top_button')
</body>

</html>
