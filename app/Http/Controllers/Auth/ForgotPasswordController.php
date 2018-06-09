<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Transformers\Json;
use App\User;
use App\Mail\Forgot;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
     */
    use SendsPasswordResetEmails;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getResetToken(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        if ($request->wantsJson()) {

            $user = User::where('email', $request->input('email'))->first();

            if (!$user) {
                return response()->json(['errors' => 'User with this email not found'], 400);
            }

            $token = $this->broker()->createToken($user);
            Mail::to($request->email)->send(new Forgot($token));

            return response()->json(['message' => 'A link to reset your password was sent to your e-mail'], 200);
        }
    }
}