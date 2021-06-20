<!-- Stored in resources/views/index.blade.php -->

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

        <div class="container-fluid px-5">
            <div class="row mx-auto text-capitalize d-flex justify-content-center">
                {{-- show contestants and judges at start of contest --}}
                @isset ($judges, $contestants)
                    <div class="col-md-auto">
                        <table class="table table-hover table-borderless">
                            <tr class="table-success">
                                <th>Judges</th>
                            </tr>

                            @foreach ($judges as $judge)
                                <tr>
                                    <td>{{ $judge }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>

                    <div class="col-md-auto">
                        <table class="table table-hover table-borderless">
                            <tr class="table-success">
                                <th>Contestants</th>
                                <th></th>
                                <th colspan="6">Genre Strength</th>
                            </tr>

                            @foreach ($contestants as $contestant)
                                <tr>
                                    <td>{{ $contestant['name'] }}</td>
                                    <td>&nbsp;&nbsp;</td>
                                    @foreach ($contestant['strength'] as $genre => $strength)
                                        <td>{{ $genre . ': ' . $strength }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </table>
                    </div>
                @endisset

                {{-- show contestants' score for the round --}}
                @isset($scores)
                    <div class="col-md-auto">
                        <table class="table table-hover table-borderless">
                            @foreach ($scores as $genre => $contestants)
                                <tr class="table-success">
                                    <th colspan="3">
                                        Round Scores
                                        <small class="text-muted">Genre: {{ $genre }}</small>
                                    </th>
                                </tr>

                           
                                @foreach ($contestants as $contestant)
                                    <tr>
                                        <td>
                                            {{ $contestant['name'] }}
                                            @if ($contestant['sick'] === true)
                                                <small class="text-muted text-lowercase">sick</small>
                                            @endif
                                        </td>
                                        <td>&nbsp;&nbsp;</td>
                                        <td>{{ $contestant['score'] }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </table>
                    </div>
                @endisset
            </div>
        </div>

        <div class="mx-auto text-center p-5">
            <a class="btn btn-outline-success btn-lg" href="{{ URL('/start') }}" role="button">Begin Contest</a>
        </div>
    </body>
</html>