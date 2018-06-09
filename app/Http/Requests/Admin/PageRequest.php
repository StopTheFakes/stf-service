<?php

namespace App\Http\Requests\Admin;
use App\Http\Requests\BaseRequest;
use Carbon\Carbon;

class PageRequest extends BaseRequest
{
    public function commonRulesOnPost()
    {
        return [
            'active_from' => 'nullable|date',
        ];
    }

    public function attributes()
    {
        return [
            'slug' => 'URL',
            'title' => 'Заголовок',
            'keywords' => 'Ключевые слова',
            'description' => 'Описание',
            'text' => 'Текст',
            'active_from' => 'Дата публикации',
        ];
    }

    public function validate()
    {
        parent::validate();

        $activeFrom = $this->request->get('active_from');
        if ($activeFrom) {
            $this->merge(['active_from' => Carbon::parse($activeFrom)]);
        }
    }
}
