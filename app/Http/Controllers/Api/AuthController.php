<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Front\ConfirmationRequest;
use App\Http\Requests\Front\RegisterRequest;
use App\Mail\Confirmation;
use App\Models\Wallet;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Front\LoginRequest;
use Auth;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{

    public function register(RegisterRequest $request)
    {
        $confirmationCode = rand(1000, 9999);

        $data = [
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'type' => (int) $request->type,
            'code' => $confirmationCode,
        ];
        $id = User::create($data)->id;

        Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
        ]);

        Mail::to($request->email)->send(new Confirmation($confirmationCode));

        Wallet::create([
            'user_id' => $id
        ]);

        return response()->json([
            'ok' => true,
        ], 200);
    }

    public function login(LoginRequest $request)
    {
        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
        ])) {
            return response()->json([
                'ok' => true,
            ]);
        }

        return response()->json([
            'ok' => false,
            'errors' => [
                'login' => 'Incorrect login or password'
            ]
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([], 200);
    }

    public function confirmation(ConfirmationRequest $request)
    {

        if (Auth::check() && Auth::user()->code == $request->code) {
            Auth::user()->active = 1;
            Auth::user()->save();

            return response()->json([
                'ok' => true
            ], 200);
        }

        return response()->json([
            'ok' => false,
            'message' => 'You entered an incorrect verification code'
        ], 200);

    }

    public function confirmationRepeat()
    {
        $confirmationCode = rand(1000, 9999);
        User::where('id', Auth::user()->id)->update(['code' => $confirmationCode]);
        Mail::to(Auth::user()->email)->send(new Confirmation($confirmationCode));

        return response()->json([
            'ok' => true,
            'message' => 'A new verification code was sent to the email specified during registration'
        ], 200);
    }

    public function getResetToken(Request $request) {



    }

}
