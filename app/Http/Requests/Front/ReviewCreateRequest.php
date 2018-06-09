<?php

namespace App\Http\Requests\Front;
use App\Http\Requests\BaseRequest;

class ReviewCreateRequest extends BaseRequest
{
    public function rulesOnPost()
    {
        return [
            'user_name' => 'required|string|min:2|max:256',
            'text' => 'required|string|min:2|max:4096',
        ];
    }

    public function attributes()
    {
        return [
            'user_name' => 'Имя и фамилия',
            'text' => 'Текст сообщения',
        ];
    }

}
