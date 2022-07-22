<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="manifest" href="/manifest.json">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="csrfToken" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Hatchery') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css', !App::environment(['local', 'testing', 'docker'])) }}?v=1.0" rel="stylesheet">
    <link href="{{ asset('css/mch.css', !App::environment(['local', 'testing', 'docker'])) }}?v=1.0" rel="stylesheet">
    <livewire:styles>

    <meta name="theme-color" content="#F2DAC7">
    <link rel="icon" type="image/x-icon" sizes="16x16" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="640x640" href="{{ asset('img/bs.png') }}">
    <link rel="apple-touch-icon" sizes="640x640" href="{{ asset('img/bs.png') }}">
    <link rel="canonical" href="{{ url()->current() }}" />

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
        @auth
        window.UserId = {{ Auth::user()->id }};
        @endauth
    </script>

</head>
<body class="h-100 d-flex flex-column mch-bg-color">
    @include('partials.nav')
    <div id="app">
        @include('partials.messages')
	<div class="container-fluid px-0 px-lg-5 mch-bg-color">
	        @yield('content')
	</div>
    </div>
    <script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "url": "{{ url('') }}",
  "name": "Badge.Team Hatchery",
  "logo": "{{ url('/img/bs.png') }}",
  "foundingDate": "2017",
  "contactPoint": {
    "@type": "ContactPoint",
    "contactType": "support",
    "email": "help@badge.team"
  }
}
    </script>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js', !App::environment(['local', 'testing', 'docker'])) }}?v=1.0"></script>
    <livewire:scripts>

    @yield('script')
    <footer class="footer mt-auto mch-font-color">
        <!-- Copyright -->
        <div class="text-center p-3">
            © {{ date('Y') }} badge.team Hatchery
            <span id="application_version">{{ App\Http\Kernel::applicationVersion() }}</span>
        </div>
        <!-- Copyright -->
    </footer>
</body>
</html>
