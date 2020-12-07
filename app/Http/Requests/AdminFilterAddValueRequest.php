<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminFilterAddValueRequest extends FormRequest
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
            'attr_group_id' => 'integer',
            'value' => 'min:3|max:100',
        ];
    }

    public function messages()
    {
        return [
            'attr_group_id.integer' => 'ID может быть только числом',
            'value.min' => 'Минимальная длина наименования - 3 символа',
            'value.max' => 'Максимальная длина наименования - 100 символов',
        ];
    }
}
