<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MessagesController extends Controller
{

    public function show(Request $request)
    {
        return view('front.messages.index');
    }

}
