<?php

namespace App\Http\Controllers\Api;

use App\Models\Finance;
use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\Validator;

class FinanceController extends Controller
{
    public function withdraw(Request $request)
    {
        $balance = Wallet::where('user_id', Auth::user()->id)->first();
        Validator::make($request->all(), [
            'amount' => 'integer|min:1|max:'.$balance->balance
        ])->validate();

        Wallet::where('user_id', Auth::user()->id)->update([
            'balance' => $balance->balance - $request->amount
        ]);
        Finance::create([
            'user_id' => Auth::user()->id,
            'amount' => $request->amount,
            'description' => 'Withdraw transaction'
        ]);

        $balance = Wallet::where('user_id', Auth::user()->id)->first();
        $logs = Finance::where('user_id', Auth::user()->id)->get();

        return response()->json([
            'balance' => $balance,
            'logs' => $logs
        ], 200);
    }


    public function deposit(Request $request)
    {
        $balance = Wallet::where('user_id', Auth::user()->id)->first();
        Validator::make($request->all(), [
            'amount' => 'integer|min:1'
        ])->validate();

        Wallet::where('user_id', Auth::user()->id)->update([
            'balance' => $balance->balance + $request->amount
        ]);
        Finance::create([
            'user_id' => Auth::user()->id,
            'amount' => $request->amount,
            'description' => 'Deposit transaction'
        ]);

        $balance = Wallet::where('user_id', Auth::user()->id)->first();
        $logs = Finance::where('user_id', Auth::user()->id)->get();

        return response()->json([
            'balance' => $balance,
            'logs' => $logs
        ], 200);
    }


    public function show(Request $request, $action)
    {
        $action = camel_case($action);
        if(method_exists($this, $action)){
            return $this->$action($request);
        }
        return abort(404);
    }


    public function checkBalance($request)
    {
        $balance = Wallet::where('user_id', Auth::user()->id)->first();
        return response()->json([
            'balance' => $balance
        ], 200);
    }

    public function indexData()
    {
        $balance = Wallet::where('user_id', Auth::user()->id)->first();
        $logs = Finance::where('user_id', Auth::user()->id)->get();
        return response()->json([
            'balance' => $balance,
            'logs' => $logs
        ], 200);
    }




}
