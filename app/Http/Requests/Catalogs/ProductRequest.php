<?php

namespace App\Http\Requests\Catalogs;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'name' => 'required',
            'brand_id' => 'required',
            'category_id' => 'required',
            'distributor_id' => 'required',
            'slug' => 'required',
            'sku' => 'required|string|max:10',
            'release_date' => 'required|date|before:today',
            'weight' => 'required|numeric',
            'box_weight' => 'required|numeric',
            'quantity' => 'required|numeric',
            'image' => 'nullable|mimes:jpg,jpeg,png|max:2048',
            'gallery' => 'nullable|array|min:1',
        ];
    }

    public function prepareForValidation()
    {
        if ($this->image == null) {
            $this->request->remove('image');
        }
        if ($this->galleies == null) {
            $this->request->remove('gallery');
        }
    }
}
