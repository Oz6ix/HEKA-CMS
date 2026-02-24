<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePharmacyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required|integer',
            'pharmacy_category_id' => 'required|integer',  
            'title' => 'required|string|max:255',              
            'company_name' => 'required|string|max:255',              
            'unit' => 'required|string|max:50',              
            'quantity' => 'required|integer',              
            'price' => 'required|numeric',
            'photo' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
            'note' => 'nullable|string|max:5000',
        ];
    }
}
