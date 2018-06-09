<?php

namespace App\Http\Requests\Admin;

class PageUpdateRequest extends PageRequest
{
    public function rulesOnPost()
    {
        return [
            'locales.*.title' => 'required|string|max:1024',
            'locales.*.keywords' => 'required|string|max:1024',
            'locales.*.description' => 'required|string|max:1024',
            'locales.*.text' => 'required|string|max:100000',
            'slug' => 'required|string|unique:pages,slug,'.$this->route()->parameters()['page'],
        ];
    }
    
    public function attributes()
    {
        $attributes = parent::attributes();
        $attributes['locales.*.title'] = 'Заголовок';
        $attributes['locales.*.keywords'] = 'Ключевые слова';
        $attributes['locales.*.description'] = 'Описание';
        $attributes['locales.*.text'] = 'Текст';
        return $attributes;
    }
}
