<?php

namespace App\Http\Requests\Front;
use App\Http\Requests\BaseRequest;

class ConfirmationRequest extends BaseRequest
{
    public function rulesOnPost()
    {
        return [
            'code' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'code' => 'Проверочный код',
        ];
    }

}
