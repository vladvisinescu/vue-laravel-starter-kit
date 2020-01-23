<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="{{ elixir('css/app.css') }}" rel="stylesheet">
        <script src="{{ asset('js/translations/packs/en.js') }}"></script>
        @if(str_replace('_', '-', app()->getLocale()) !== 'en')
            <script src="{{ asset('js/translations/packs/' . str_replace('_', '-', app()->getLocale()) . '.js') }}"></script>
        @endif
    </head>
    <body>
        <div id="app">
            <app></app>
        </div>
        @routes
        <script src="{{ elixir('js/app.js') }}"></script>
    </body>
</html>
