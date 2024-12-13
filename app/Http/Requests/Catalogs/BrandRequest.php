<?php

namespace App\Http\Requests\Catalogs;

use App\Models\Users\Customers;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use function request;

class BrandRequest extends FormRequest
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
        if (request()->routeIs('brands.store')) {
            $slugRule = 'required|unique:brands,slug';
        } elseif (request()->routeIs('brands.update')) {
            $slugRule = 'required|unique:brands,slug,' . $this->id;
        }
        return [
            'name' => 'required',
            'slug' => $slugRule,
//            'image' => ['nullable', 'mimes:jpg,jpeg,png', 'max:2048']
        ];
    }

    public function prepareForValidation()
    {
        if ($this->avatar == null) {
            $this->request->remove('avatar');
        }
    }
}
