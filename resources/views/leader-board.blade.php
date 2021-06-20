<!-- Stored in resources/views/leader-board.blade.php -->

    @include('shared.head')

    <div class="container-fluid px-5">
        <div class="row mx-auto text-capitalize d-flex justify-content-center">
            {{-- show contestants and judges at start of contest --}}
            @isset ($leaders, $all_time_highs)
                <div class="col-md-auto">
                    <table class="table table-hover table-borderless">
                        <tr class="table-success">
                            <th colspan="3">All time high</th>
                        </tr>

                        @foreach ($all_time_highs as $ah)
                            <tr>
                                <td>{{ $ah['winner'] }}</td>
                                <td>&nbsp;&nbsp;</td>
                                <td>{{ $ah['winning_score'] }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>

                <div class="col-md-auto">
                    <table class="table table-hover table-borderless">
                        <tr class="table-success">
                            <th colspan="3">Last five winners</th>
                            <th></th>
                        </tr>

                        @foreach ($leaders as $leader)
                            <tr>
                                <td>{{ $leader['winner'] }}</td>
                                <td>&nbsp;&nbsp;</td>
                                <td>{{ $leader['winning_score'] }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            @endisset
        </div>
    </div>

    @include('shared.tail')
