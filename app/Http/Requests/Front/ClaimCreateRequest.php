<?php

namespace App\Http\Requests\Front;

use App\Http\Requests\BaseRequest;

class ClaimCreateRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $data = $this->data;
        return [
            'title'=>'required|string',
            'type'=>'required',
            'country_id'=>'required',
            'city_id'=>'required',
            'category'=>'required|string',
            'object'=>'present|array',
            'description'=>'required|min:150',
//            'picture_method'=>'sometimes',
//            'picture_cost'=>'sometimes|numeric',
//            'video_method'=>'sometimes',
//            'video_cost'=>'sometimes|numeric',
//            'screenshot_method'=>'sometimes',
//            'screenshot_cost'=>'sometimes|numeric',
            'usage_rights' => 'required|string',
            'rights' => 'array',
            'confirmation' => 'required'
        ];
    }
}
