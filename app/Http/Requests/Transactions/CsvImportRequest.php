<?php

namespace App\Http\Requests\Transactions;

use Illuminate\Foundation\Http\FormRequest;

class CsvImportRequest extends FormRequest
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
        $rules = [];
        if (request()->routeIs('receipts.import_preview')) {
            $rules = [
                'csv_file' => 'required|mimes:csv|file',
                'order_number' => 'required|exists:orders,order_number',
            ];
        } elseif (request()->routeIs('receipts.process')) {
            $rules = [
                'filename' => 'required|string'
            ];
        }
        return $rules;
    }
}
