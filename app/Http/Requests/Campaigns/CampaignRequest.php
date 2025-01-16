<?php

namespace App\Http\Requests\Campaigns;

use App\Models\Campaigns\Campaigns;
use Illuminate\Foundation\Http\FormRequest;

class CampaignRequest extends FormRequest
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
        $type = $this->input('type');
        $rules = [
            'name' => 'required',
            'slug' => 'required',
            'type' => 'required',
            'status' => 'required',
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
        ];
        if ($type != null && $type == Campaigns::TYPE_DISCOUNT) {
            $rules['discount_value'] = 'required|numeric|max:100';
        } elseif ($type == Campaigns::TYPE_BUNDLE) {
            $rules['bundle_value'] = 'required|numeric';
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'discount_value.max' => 'Percentage value cannot be more than 100',
            'discount_value.numeric' => 'Percentage value must be a number',
        ];
    }
}
