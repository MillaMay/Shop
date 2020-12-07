<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminCurrencyAddRequest extends FormRequest
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
            'code' => 'min:3|max:3|string',
            'value' => 'regex:/^\d+(\.\d{1,2})?$/', // Для целого числа или числа с точкой (после точки допустимо только 2 цифры)
        ];
    }

    public function messages()
    {
        return [
            'code.min' => 'Минимальная длина кода - 3 символа',
            'code.max' => 'Максимальная длина кода - 3 символа',
            'code.string' => 'Код валюты может быть только строкой',
            'value.regex' => 'Значение может быть или целым числом, или числом с плавающей точкой',
        ];

    }
}
