<!-- Stored in resources/views/shared/tail.blade.php -->
        
        <div class="mx-auto text-center p-5">
            @if (isset($rounds))
                <a class="btn btn-outline-success btn-lg" href="{{ URL('/play') }}" role="button">
                    Next Round
                    <small class="badge bg-secondary">{{ $rounds }} more</small>
                </a>
            @else
                <a class="btn btn-outline-success btn-lg" href="{{ URL('/leader-board') }}" role="button">Leader Board</a>
                <a class="btn btn-outline-success btn-lg" href="{{ URL('/start') }}" role="button">Start Contest</a>

                @if ($_SERVER['REQUEST_URI'] != '/')
                    <div class="mx-auto p-3">
                        <a class="text-muted" href="{{ URL('/') }}"><small>Back Home</small></a>
                    </div>
                @endif
            @endif            
        </div>
    </body>
</html>