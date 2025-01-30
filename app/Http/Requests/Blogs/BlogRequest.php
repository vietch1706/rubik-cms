<?php

namespace App\Http\Requests\Blogs;

use Illuminate\Foundation\Http\FormRequest;

class BlogRequest extends FormRequest
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
            'employee_id' => 'required|exists:employees,id',
            'category_id' => 'required|exists:categories,id',
            'title' => 'required',
            'slug' => 'required|unique:blogs,slug',
            'content' => 'required',
            'date' => 'required',
            'thumbnail' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'employee_id.required' => 'Employee is required.',
            'employee_id.exists' => 'Employee not found.',
            'category_id.required' => 'Category is required.',
            'category_id.exists' => 'Category not found.',
            'title.required' => 'Title is required.',
            'slug.required' => 'Slug is required.',
            'content.required' => 'Content is required.',
            'date.required' => 'Date is required.',
        ];
    }
}
