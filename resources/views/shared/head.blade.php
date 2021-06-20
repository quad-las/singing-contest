<!-- Stored in resources/views/shared/head.blade.php -->

<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link rel='shortcut icon' type='image/x-icon' href="{{ URL::asset('images/favicon-16x16.png') }}">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" intescgrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
        
        <title>{{ env('APP_NAME') }}</title>
    </head>

    <body>
        <div class="mx-auto p-2">
            <img src="{{ URL::asset('images/logo-152x152.png') }}" class="rounded mx-auto d-block" alt="InnoGames Logo">
            <h1 class="display-4 text-center text-success">InnoGames Singing Contest</h1>
        </div>