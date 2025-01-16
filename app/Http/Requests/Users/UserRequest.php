<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use function request;

class UserRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        if (request()->routeIs('profile.update')) {
            $rules = [
                'first_name' => 'required',
                'last_name' => 'required',
                'phone' => 'required|numeric|unique:users,phone,' . $this->id,
                'gender' => 'numeric',
                'avatar' => 'nullable|mimes:jpg,jpeg,png|max:2048'
            ];
        } elseif (request()->routeIs('profile.changePassword.update')) {
            $rules = [
                'password' => 'required',
                'new_password' => 'required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
                'confirm_password' => 'required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/|same:new_password'
            ];
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'password.required' => 'Please enter password.',
            'new_password.required' => 'Please enter new password.',
            'new_password.regex' => 'error',
            'confirm_password.required' => 'Please enter confirm password.',
            'confirm_password.regex' => 'error',
        ];
    }
}
