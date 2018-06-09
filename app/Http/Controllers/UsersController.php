<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function settings()
    {
        return view('front.users.settings');
    }
}
