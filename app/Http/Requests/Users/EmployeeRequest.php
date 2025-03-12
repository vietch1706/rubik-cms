<?php

namespace App\Http\Requests\Users;

use App\Models\Users\Employees;
use Illuminate\Foundation\Http\FormRequest;
use function request;

class EmployeeRequest extends FormRequest
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
        if (request()->routeIs('employees.store')) {
            $passwordRule = 'required|min:6';
            $emailRule = 'required|email|unique:users,email';
            $phoneRule = 'required|numeric|unique:users,phone';
        } elseif (request()->routeIs('employees.update')) {
            $employee = Employees::find($this->id);
            $userID = $employee->users()->first()->id;
            $passwordRule = 'sometimes';
            $emailRule = 'required|email|unique:users,email,' . $userID;
            $phoneRule = 'required|numeric|unique:users,phone,' . $userID;
        }

        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => $emailRule,
            'phone' => $phoneRule,
            'salary' => 'numeric|min:1',
            'gender' => 'numeric',
            'password' => $passwordRule . '|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            'avatar' => 'nullable|mimes:jpg,jpeg,png|max:2048'
        ];
    }

    public function prepareForValidation()
    {
        if ($this->password == null) {
            $this->request->remove('password');
        }
        if ($this->avatar == null) {
            $this->request->remove('avatar');
        }
    }
}
