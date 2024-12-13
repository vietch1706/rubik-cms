<?php

namespace App\Http\Requests\Catalogs;

use Illuminate\Foundation\Http\FormRequest;
use function dd;
use function request;

class CategoryRequest extends FormRequest
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
        if (request()->routeIs('categories.store')) {
            $slugRule = 'required|unique:categories,slug';
        } elseif (request()->routeIs('categories.update')) {
            $slugRule = 'required|unique:categories,slug,' . $this->id;
        }
        return [
            'parent_id' => 'nullable|exists:categories,id',
            'name' => 'required',
            'slug' => $slugRule,
        ];
    }
}
