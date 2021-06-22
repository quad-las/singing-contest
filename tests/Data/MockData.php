<?php

class MockData
{
    const TABLE = 'test-contests';
    const VIEW = 'Illuminate\View\View';
    const REDIRECT = 'Illuminate\Http\RedirectResponse';
    const GENRE = 'pop';

    const NUMBER_OF_ROUNDS = 6;
    const ROUNDS_LEFT = 3;

    const CONTESTANTS = [
        [
            'name' => 'Dr. Kurtis Cole',
            'strength' => ['rock' => 3, 'country' => 6, 'pop' => 9, 'disco' => 4, 'jazz' => 10, 'the blues' => 2],
        ],
        [
            'name' => 'Dr. Solon Reilly V',
            'strength' => ['rock' => 1, 'country' => 4, 'pop' => 6, 'disco' => 2, 'jazz' => 2, 'the blues' => 2],
        ]
    ];

    const JUDGES = ['mean', 'friendly', 'honest'];

    const ROUND_SCORES = [
        ['name' => 'Dr. Kurtis Cole', 'score' => 14, 'sick' => false],
        ['name' => 'Dr. Solon Reilly V', 'score' => 23, 'sick' => true],
    ];

    const LEADER_BOARD = [
        "leaders" => [
            ["winner" => "Luella Kuhn", "winning_score" => 97],
            ["winner" => "Alta Lang IV", "winning_score" => 82]
        ],
        "all_time_highs" => [
            ["winner" => "Freddy Walter", "winning_score" => 105]
        ]
    ];

    const WINNER = [
        ["Bennett Huel" => 117]
    ];
}
