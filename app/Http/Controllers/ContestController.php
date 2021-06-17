<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contest;
use App\Domain\Contestants;

class ContestController extends Controller
{
    public function create()
    {
        // TODO: contest


        // TODO: get consultants 
        $contestants = new Contestants();
        $contestants = $contestants->generateContestants();

        // TODO: get jugdes 

        return $contestants;
    }
}
