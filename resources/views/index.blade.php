<!-- Stored in resources/views/index.blade.php -->
    @include('shared.head')

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

                {{-- show champion(s) :) --}}
                @isset($winners)
                    <div class="col-md-auto">
                        <table class="table table-hover table-borderless">
                            <tr class="table-success">
                                <th colspan="3">
                                    The Winner(s)!!!
                                </th>
                            </tr>
                    
                            @foreach (array_values($winners) as $winner)
                                <tr>
                                    <td>{{ array_keys($winner)[0] }}</td>
                                    <td>&nbsp;&nbsp;</td>
                                    <td class="fw-bold">{{ array_values($winner)[0] }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                @endisset
            </div>
        </div>

    @include('shared.tail')