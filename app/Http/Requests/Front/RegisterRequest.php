<?php

namespace App\Http\Requests\Front;
use App\Http\Requests\BaseRequest;

class RegisterRequest extends BaseRequest
{
    public function rulesOnPost()
    {
        return [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'type' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'email' => 'Email',
            'password' => 'Password',
            'type' => 'Type'
        ];
    }

}