<?php

namespace App\Http\Requests\Transactions;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
        return [
            'distributor_id' => 'required|exists:distributors,id',
            'status' => 'required',
            'note' => 'nullable|string|max:255',
            'products' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'distributor_id.required' => 'Please select distributor',
            'products.required' => 'Please select at least one product',
        ];
    }
}
