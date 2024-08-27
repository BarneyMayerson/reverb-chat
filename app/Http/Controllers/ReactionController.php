<?php

namespace App\Http\Controllers;

use App\Events\ReactionEvent;
use Illuminate\Http\Request;

class ReactionController extends Controller
{
    public function store(Request $request)
    {
        event(
            new ReactionEvent($request->input('buttonId'), $request->input('emoji'))
        );
    }
}
