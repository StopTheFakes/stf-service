<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaseRequest extends FormRequest
{

    public function __construct()
    {
        parent::__construct();
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $method = $this->method();
        $isValid = in_array($method, ['POST', 'PUT', 'PATCH']);
        if ($isValid) {
            $rules = [];
            if (method_exists($this, 'commonRulesOnPost')) {
                $rules = $this->commonRulesOnPost();
            }
            return array_merge($rules, $this->rulesOnPost());
        }

        return [];
    }
}
