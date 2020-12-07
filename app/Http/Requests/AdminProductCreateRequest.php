<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminProductCreateRequest extends FormRequest
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
            'title' => 'required|min:3|max:55|string',
            'count' => 'required|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'title.min' => 'Минимальная длина наименования - 3 символа',
            'title.max' => 'Максимальная длина наименования - 55 символов',
            'count.integer' => 'Допускаются только цифры (целое число) для кол-ва товара',
        ];
    }
}
