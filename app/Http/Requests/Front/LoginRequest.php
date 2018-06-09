<?php

namespace App\Http\Requests\Front;
use App\Http\Requests\BaseRequest;

class LoginRequest extends BaseRequest
{
    public function rulesOnPost()
    {
        return [
            'email' => 'required|string',
            'password' => 'required|string',
        ];
    }

    public function attributes()
    {
        return [
            'email' => 'Email',
            'password' => 'Password',
        ];
    }

}
