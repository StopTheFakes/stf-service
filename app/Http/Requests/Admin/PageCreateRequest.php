<?php

namespace App\Http\Requests\Admin;

class PageCreateRequest extends PageRequest
{
    public function rulesOnPost()
    {
        return [
            'slug' => 'required|string|unique:pages,slug',
            'title' => 'required|string|max:255',
            'seo_keywords' => 'required|string',
            'description' => 'required|string|max:255',
            'content' => 'required|string|max:100000',
        ];
    }
}
