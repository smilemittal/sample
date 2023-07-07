<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel='icon' src="{{url('/resources/img/favicon.png')}}" type='image/x-icon' />
    <title inertia>{{ config('app.name', 'Themey') }}</title>

    <link rel="icon" href="{{url('/images/favicon.png')}}" sizes="192x192" />
    <link rel="apple-touch-icon" href="{{url('/images/favicon.png')}}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/dropdown.css','resources/css/custom.css',
     'resources/js/dropdown.js'])
  <script>
    window.APP = {
        domain: "{{ env('APP_URL') }}",
    };
</script>
    <!-- Scripts -->
    @routes
    @if (config('app.env') == 'local')
        <script type="text/javascript" src="{{ url('/js/lang.js') }}"></script>
    @else
        @vite('resources/js/lang/' . app()->getLocale() . '.js') {{-- auth()->user() ? auth()->user()->language : --}}
    @endif
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])

    @inertiaHead

</head>

<body class="font-sans antialiased">
    @inertia
    {{-- <script>
        const picker = document.querySelector("#timepicker-format");
        const
            tpFormat24 = new te.Timepicker(picker, {
                format24: true,
            });
    </script> --}}

<script src="https://embed.typeform.com/next/embed.js"></script>
</body>

</html>
