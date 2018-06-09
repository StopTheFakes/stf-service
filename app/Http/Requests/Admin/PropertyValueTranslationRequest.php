<?php

namespace App\Http\Requests\Admin;
use App\Http\Requests\BaseRequest;
use Translation;

class PropertyValueTranslationRequest extends BaseRequest
{
    public function rulesOnPost()
    {
        return [
            'translation' => 'required|string|max:256',
        ];
    }

    public function attributes()
    {
        return [
            'translation' => Translation::translateKey('PROPERTY_VALUE'),
        ];
    }
}
