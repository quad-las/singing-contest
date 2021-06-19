<!-- Stored in resources/views/index.blade.php -->

<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link rel='shortcut icon' type='image/x-icon' href="{{ URL::asset('images/favicon-16x16.png') }}">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
        
        <title>{{ env('APP_NAME') }}</title>
    </head>

    <body>
        <div class="mx-auto p-5">
            <h1 class="display-3 text-center text-success">InnoGames Singing Contest</h1>
        </div>

        <div class="mx-auto text-center p-5">
            <a class="btn btn-outline-success btn-lg" href="#" role="button">Begin Contest</a>
        </div>

        <div>

        </div>
    </body>
</html>