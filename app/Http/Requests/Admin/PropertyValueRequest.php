<?php

namespace App\Http\Requests\Admin;
use App\Http\Requests\BaseRequest;
use Translation;

class PropertyValueRequest extends BaseRequest
{
    public function rulesOnPost()
    {
        return [
            'value' => 'required|array',
            'value.*' => 'required|string|max:256',
        ];
    }

    public function attributes()
    {
        return [
            'value.*' => Translation::translateKey('PROPERTY_VALUE'),
        ];
    }
}
