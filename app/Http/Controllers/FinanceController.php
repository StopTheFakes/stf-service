<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function show(Request $request)
    {
        return view('front.finance.index');
    }
}
