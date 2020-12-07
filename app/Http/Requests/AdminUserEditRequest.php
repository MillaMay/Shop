<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminUserEditRequest extends FormRequest
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
        $id = $_POST['id'];

        return [
            'name' => 'required|min:2|max:20|string',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                \Illuminate\Validation\Rule::unique('users')->ignore($id), /* Для редактирования:
            юзер по данному $id может почту не менять - игнорирование его почты */
            ],
            'password' => 'nullable|string|confirmed', /* Также для редактирования:
        nullable - чтобы юзер не менял свой пароль, если не хочет */
        ];
    }

    public function messages()
    {
        return [
           'name.min' => 'Минимальная длина имени - 2 символа',
           'email.unique' => 'Этот Email уже существует',
           'password.confirmed' => 'Пароли не совпадают',
        ];
    }
}
