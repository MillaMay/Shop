<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminCategoryUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|min:3|max:300',
            'slug' => 'max:300',
            'keywords' => 'min:3|string|max:500',
            'description' => 'min:5|string|max:700',
            'parent_id' => 'integer',
        ];
    }

    public function messages()
    {
        return [
            'title.min' => 'Минимальная длина наименования - 3 символа',
            'keywords.min' => 'Минимальная длина ключевых слов - 3 символа',
            'keywords.string' => 'Ключевые слова должны быть текстом',
            'description.min' => 'Минимальная длина описания - 5 символов',
            'description.string' => 'Описание должно быть текстом',
        ];
    }
}
