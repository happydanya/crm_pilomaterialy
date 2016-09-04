<!DOCTYPE html>
<html lang="en">
{!! $header !!}
<body id="app-layout">
    {!! $menu !!}

    @yield('content')

    <!-- JavaScripts -->
    {!! $footer !!}
    @yield('custom-scripts')
    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
</body>
</html>
