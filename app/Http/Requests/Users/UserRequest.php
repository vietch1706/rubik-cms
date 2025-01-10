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
        } elseif (request()->routeIs('profile.change-password')) {
            $rules = [
                'password' => 'required',
                'new_password' => 'required',
                'confirm_password' => 'required|same:new_password'
            ];
        }
        return $rules;
    }
}
