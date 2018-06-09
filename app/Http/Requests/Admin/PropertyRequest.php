<?php

namespace App\Http\Requests\Admin;
use App\Http\Requests\BaseRequest;
use Translation;

class PropertyRequest extends BaseRequest
{
    public function rulesOnPost()
    {
        return [
            'name' => 'array',
            'name.*' => 'required|string|max:256',
        ];
    }

    public function attributes()
    {
        return [
            'name.*' => Translation::translateKey('PROPERTY_NAME'),
        ];
    }
}
