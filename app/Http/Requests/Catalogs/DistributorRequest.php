<?php

namespace App\Http\Requests\Catalogs;

use App\Models\Users\Customers;
use Illuminate\Foundation\Http\FormRequest;
use function request;

class DistributorRequest extends FormRequest
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
        if (request()->routeIs('distributors.store')) {
            $emailRule = 'required|email|unique:distributors,email';
            $phoneRule = 'required|numeric|unique:distributors,phone';
        } elseif (request()->routeIs('distributors.update')) {
            $emailRule = 'required|email|unique:distributors,email,' . $this->id;
            $phoneRule = 'required|numeric|unique:distributors,phone,' . $this->id;
        }
        return [
            'name' => 'required',
            'address' => 'required',
            'country' => 'required',
            'phone' => $phoneRule,
            'email' => $emailRule,
        ];
    }
}
